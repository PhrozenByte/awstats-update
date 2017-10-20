<?php
    // get base path
    if (!defined('BASE_PATH')) {
        if (isset($_SERVER['AWSTATS_BASE_PATH'])) {
            define('BASE_PATH', $_SERVER['AWSTATS_BASE_PATH']);
        } elseif (isset($_SERVER['REDIRECT_BASE_PATH'])) {
            define('BASE_PATH', $_SERVER['REDIRECT_AWSTATS_BASE_PATH']);
        } else {
            define('BASE_PATH', __DIR__);
        }
    }

    // get types
    if (!defined('TYPES')) {
        if (isset($_SERVER['AWSTATS_TYPES'])) {
            define('TYPES', $_SERVER['AWSTATS_TYPES']);
        } elseif (isset($_SERVER['REDIRECT_AWSTATS_TYPES'])) {
            define('TYPES', $_SERVER['REDIRECT_AWSTATS_TYPES']);
        } else {
            define('TYPES', '');
        }
    }

    // prepare globals
    $data = array();
    $error = array();

    // walk through given types
    if (TYPES) {
        $types = explode('/', TYPES);
        foreach ($types as $typeData) {
            // types can have a name:description syntax
            if (strpos($typeData, ':') !== false) {
                list($type, $typeDesc) = explode(':', $typeData);
            } else {
                $type = $typeData;
                $typeDesc = '';
            }

            // prepare data
            $data[$type] = array(
                'name' => $type,
                'desc' => $typeDesc
            );

            // unknown type
            if (!file_exists(BASE_PATH.'/'.$type) || !is_dir(BASE_PATH.'/'.$type)) {
                $data[$type]['error'] = 'No such AWStats directory "'.BASE_PATH.'/'.$type.'"';
                continue;
            }

            // walk through domains
            $domains = array_reverse(scandir(BASE_PATH.'/'.$type, 1));
            foreach ($domains as $domain) {
                if (($domain === '.') || ($domain === '..')) {
                    continue;
                }

                // prepare domain data
                if (!isset($data[$type]['domains'])) {
                    $data[$type]['domains'] = array();
                }
                $data[$type]['domains'][$domain] = array();

                // walk through years
                $years = scandir(BASE_PATH.'/'.$type.'/'.$domain);
                sort($years, SORT_NATURAL | SORT_FLAG_CASE);
                $years = array_reverse($years);

                foreach ($years as $year) {
                    if (($year === '.') || ($year === '..')) {
                        continue;
                    }

                    // walk through months
                    $months = scandir(BASE_PATH.'/'.$type.'/'.$domain.'/'.$year);
                    sort($months, SORT_NATURAL | SORT_FLAG_CASE);
                    $months = array_reverse($months);

                    foreach ($months as $month) {
                        if (($month === '.') || ($month === '..')) {
                            continue;
                        }

                        // add date to domain data
                        try {
                            $data[$type]['domains'][$domain][] = new DateTime($year.'-'.$month.'-1 00:00:00 UTC');
                        } catch (\Exception $e) {
                            $data[$type]['domains'][$domain][] = $year.'/'.$month;
                        }
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="de">
    <head>

        <title>AWStats :: stats.phrozenbyte.com</title>

        <meta charset="utf-8" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css" />

        <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <style>
            .panel-heading.disabled a:hover {
                cursor: not-allowed;
            }
        </style>

    </head>
    <body role="document">

        <div class="container" role="main">
            <div class="page-header">
                <h1>PhrozenByte.com AWStats</h1>
            </div>

            <?php if (empty($data)): ?>
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    No AWStats available; you must specify the environment variable "AWSTATS_TYPES"
                </div>
            <?php endif; ?>

            <?php $typeId = 0; ?>
            <?php foreach ($data as $type => $typeData): ?>
                <?php if (($typeId % 3) === 0): ?>
                    <?php if ($typeId !== 0): ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                <?php endif; ?>

                <div class="col-sm-12 col-md-4">
                    <h3><?php echo $typeData['desc']; ?></h3>
                    <?php if (isset($typeData['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $typeData['error']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($typeData['domains'])): ?>
                        <div class="panel-group" id="accordion-<?php echo $type; ?>">
                            <?php $domainId = 0; ?>
                            <?php foreach ($typeData['domains'] as $domain => $domainData): ?>
                                <div class="panel panel-default">
                                    <?php if (!empty($domainData)): ?>
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion-<?php echo $type; ?>" href="#accordion-<?php echo $type; ?>-domain<?php echo $domainId; ?>">
                                                    <?php echo $domain; ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <ul id="accordion-<?php echo $type; ?>-domain<?php echo $domainId; ?>" class="list-group panel-collapse collapse">
                                            <?php for ($i = 0, $max = count($domainData); $i < $max; $i++): ?>
                                                <?php if (is_a($domainData[$i], 'DateTime')): ?>
                                                    <?php $year = $domainData[$i]->format('Y'); ?>
                                                    <?php $month = $domainData[$i]->format('m'); ?>
                                                    <li class="list-group-item">
                                                        <a href="<?php echo $type.'/'.$domain.'/'.$year.'/'.$month.'/'; ?>">
                                                            <?php echo $domainData[$i]->format('F Y'); ?>
                                                        </a>
                                                    </li>
                                                <?php elseif (preg_match('/^[0-9]{4}\/all$/', $domainData[$i])): ?>
                                                    <?php $year = substr($domainData[$i], 0, 4); ?>
                                                    <li class="list-group-item">
                                                        <a href="<?php echo $type.'/'.$domain.'/'.$year.'/all/'; ?>">
                                                            <?php echo DateTime::createFromFormat('Y', $year)->format('Y'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="panel-heading disabled">
                                            <h4 class="panel-title">
                                                <a href="#" onclick="return false;"><?php echo $domain; ?></a>
                                            </h4>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php $domainId++; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                            <span class="sr-only">Info:</span>
                            No data
                        </div>
                    <?php endif; ?>
                </div>

                <?php $typeId++; ?>
            <?php endforeach; ?>
            <?php if ($typeId > 0): ?>
                </div>
            <?php endif; ?>
        </div>

    </body>
</html>
