<?php
try
{
    function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $fileinfo = new SplFileInfo($path);
                if($fileinfo->getExtension() == "php")
                {
                    $results[] = $path;
                    include $path;
                }
            } else if($value != "." && $value != "..") {
                getDirContents($path, $results);
            }
        }

        return $results;
    }

    getDirContents("app");
    $ba = new \Providers\BAServiceProvider;
    $request    =   $ba->request("team/frc118/event/2018txda/status");
    print("<pre>");
    print_r( $request['body'] );
    print("</pre>");
}
catch (Exception $e)
{
    echo $e;
}