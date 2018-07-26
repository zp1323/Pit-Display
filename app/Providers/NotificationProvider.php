<?php
/*
 * cURL Service Provider for the blue alliance
 */
namespace Providers;
use \PDO;

class NotificationProvider
{
  private $db;
  
  public function __construct()
  {
        $this->db = DBConnection::getInstance();
  }
  
  public function getActive()
  {
  	// Returns all notifications that have not been cleared
  	$sql = $this->db->prepare("SELECT * FROM slash_commands WHERE `acknowledged` = 0 ORDER BY id DESC");
        $sql->execute();
        $notifications = $sql->fetchAll();

        if(empty($notifications)) return false;
        
        return $notifications;  
  }
  
  public function getLast($includeAck = false)
  {
  	// Returns newest notification, will return null if all notifications are acknowledged and flag is not set to true
  }

  public function get($id)
  {
    // Returns notification by id
    $sql = $this->db->prepare("SELECT * FROM slash_commands WHERE `id` = :id LIMIT 1");
    $sql->bindParam(':id',     $id,  PDO::PARAM_INT);
    $sql->execute();
    $notification = $sql->fetch();

    return $notification;  
  }
  
  public function ack($id)
  {
    // Load notification data into variable storage
    $n  = $this->get($id);

  	// Acknowledge notification
    try
    {
    	$sql = $this->db->prepare("UPDATE `slash_commands` SET `acknowledged` = '".time()."' WHERE `slash_commands`.`id` = :id;");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();

      $return['status']   = "success";
      $return['message']  = "The slash command with id ".$id." was successfully acknowledged.";

      // Send Slack notification that it was acknowledged
      $url  = $n['response_url'];
      $type = "ephemeral";
      $message = "Your notification was acknowledged in the pit.  Thanks for sharing!";
      $return['responses'][]  = $this->slackResponse($url, $type, $message, $n['text']);
    }
    catch (Exception $e)
    {
      return $e;
    }
  	
  	
  }

  public function ackAll()
  {
    // Store all of the records before we clear them - we will need the response urls!
    $all  = $this->getActive();

    // Acknowledge notification
    try
    {
      $sql = $this->db->prepare("UPDATE `slash_commands` SET `acknowledged` = '".time()."' WHERE `slash_commands`.`acknowledged` = 0;");
      $sql->execute();

      $return['status']   = "success";
      $return['message']  = "All pending slash commands were successfully acknowledged.";

      // Send Slack notification that it was acknowledged
      foreach($all as $v)
      {
        $url  = $v['response_url'];
        $type = "ephemeral";
        $message = "Your notification was acknowledged in the pit.  Thanks for sharing!";
        $return['responses'][]  = $this->slackResponse($url, $type, $message, $v['text']);
      }
      
      return $return;
    }
    catch (Exception $e)
    {
      return $e;
    }
  }


  public function slackResponse($url, $type, $message, $original_msg)
  {
    $data = json_encode(array(
                "response_type"       =>  "".$type."",
                "attachments"         => array(array(
                  "fallback"  => "".$message."",
                  "pretext"  => "".$message."",
                  "color"   =>  "good",
                  "text"    =>  "".$original_msg."",
                  "ts"      => "".time().""
                ))
              ));
    $return['data'] = $data;
    //$data = "payload=" . $data;

    $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

    $return['url']      = $url;
    $return['type']     = $type;
    $return['message']  = $message;
    $return['original_msg']  = $original_msg;
    $return['result']   = $result;
    return $return;
  }

}

