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

    $iterator = $client->getIterator('Scan', array(
        'TableName' => 'WaffleIt'
    ));
    arsort($iterator);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>WaffleIt</title>
    <link href="https://fonts.googleapis.com/css?family=Chewy" rel="stylesheet">
    <link rel="icon" type="image/png" href="waffle-icon-trans.png">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="padding-top: 50px;">
        <h1 style="margin-bottom: 40px; font-family: 'Chewy', cursive;"><img src="waffle-icon.jpg" style="width: 80px; top: -5px; position: relative; margin-right: 10px;" /> WaffleIt</h1>

        <ul class="list-unstyled">
        <?php foreach ($iterator as $item): ?>
            <?php if ($item['active']['BOOL']): ?>
                <li class="media" style="border-bottom: 1px solid #f1f2f2; padding-bottom: 30px; margin-bottom: 30px;">
                    <div class="media-body">
                        <h3 class="mt-0">
                            <a href="<?php echo $item['link']['S']; ?>"><?php echo $item['title']['S']; ?></a>
                            <small class="text-muted" style="font-size: 1.2rem; margin-left: 5px;"><?php echo $item['srvname']['S']; ?></small>
                        </h3>
                        <?php echo $item['body']['S']; if (substr($item['body']['S'], -1) !== '.'){ echo '...'; } ?> <a href="<?php echo $item['link']['S']; ?>">Read More &raquo;</a>
                    </div>

                    <?php if ($item['thumb']['S']): ?>
                        <img class="d-flex ml-3 img-thumbnail" style="max-width: 125px;" src="<?php echo $item['thumb']['S']; ?>" alt="<?php echo $item['title']['S']; ?>">
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"></script>
</body>
</html>