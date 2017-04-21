<?php
    require 'aws/aws-autoloader.php';
    date_default_timezone_set('America/New_York');

    use Aws\DynamoDb\DynamoDbClient;

    $client = new DynamoDbClient([
        'region'  => 'us-east-1',
        'version' => 'latest',
        'credentials' => [
            'key'    => 'AKIAIZ7563FPRHUAZSOQ',
            'secret' => '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',
        ]
    ]);

    $input = file_get_contents("php://input");
    $json = json_decode($input, TRUE);
    var_dump($input);

    $token = "xoxp-3741029006-3741029024-171809537074-ac2519b148074ec27552b7ed54c0626b";

    if ($json['event']['reaction'] == 'waffle'){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/groups.history?token=" . $token . "&channel=" . $json['event']['item']['channel'] . "&latest=" . $json['event']['item']['ts'] . "&inclusive=true&count=1"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch);
        curl_close($ch);
        $jsonoutput = json_decode($output, TRUE);

        

        if (!empty($jsonoutput['messages'][0]['attachments'])){
            $putarray = array();
            $timestamp = time();

            if (!empty($jsonoutput['latest'])){
                $putarray['ts'] = array('N' => strval($jsonoutput['latest']));
            }
            if (!empty($timestamp)){
                $putarray['id'] = array('S' => strval($timestamp));
            }
            if (!empty($jsonoutput['messages'][0]['attachments'][0]['title_link'])){
                $putarray['link'] = array('S' => strval($jsonoutput['messages'][0]['attachments'][0]['title_link']));
            }
            if (!empty($jsonoutput['messages'][0]['attachments'][0]['title'])){
                $putarray['title'] = array('S' => strval($jsonoutput['messages'][0]['attachments'][0]['title']));
            }
            if (!empty($jsonoutput['messages'][0]['attachments'][0]['service_name'])){
                $putarray['srvname'] = array('S' => strval($jsonoutput['messages'][0]['attachments'][0]['service_name']));
            }
            if (!empty($jsonoutput['messages'][0]['attachments'][0]['text'])){
                $putarray['body'] = array('S' => strval($jsonoutput['messages'][0]['attachments'][0]['text']));
            }
            if (!empty($jsonoutput['messages'][0]['attachments'][0]['thumb_url'])){
                $putarray['thumb'] = array('S' => strval($jsonoutput['messages'][0]['attachments'][0]['thumb_url']));
            }
            if ($json['event']['type'] == 'reaction_added'){
                $putarray['active'] = array('BOOL' => TRUE);
            } elseif ($json['event']['type'] == 'reaction_removed'){
                $putarray['active'] = array('BOOL' => FALSE);
            }
            $response = $client->putItem(array(
                'TableName' => 'WaffleIt', 
                'Item' => $putarray
            ));
        }
    }
?>