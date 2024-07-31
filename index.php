<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网盘资源搜索</title>
    <!-- 引入Bootstrap的CSS文件（国内镜像源） -->
    <link href="https://cdn.staticfile.org/twitter-bootstrap/5.1.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入Bootstrap的JS文件（国内镜像源） -->
    <script src="https://cdn.staticfile.org/twitter-bootstrap/5.1.1/js/bootstrap.bundle.min.js"></script>
    <!-- Material Icons（国内镜像源） -->
    <link href="https://fonts.loli.net/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="shortcut icon" href="./favicon.ico">

    <style>
        .search-form {
            margin-bottom: 20px;
        }
        .result-item {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 5px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease-in-out;
        }
        .result-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        @media (max-width: 991.98px) {
            .result-item {
                padding: 15px;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <header class="text-center mb-5">
        <h1>网盘资源搜索</h1>
    </header>

    <main class="text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- 搜索表单 -->
                <form method="GET" action="" class="search-form mb-3" id="searchForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="q" name="q" placeholder="输入搜索词" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" required>
                        <button class="btn btn-primary" type="submit"><i class="material-icons">search</i></button>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>提示：</strong> 搜不到的敏感关键词可以分开，例如：庆余年→庆余
                    </div>
                    <div class="alert alert-primary alert-dismissible fade show mt-3">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>推广：</strong><span ><a style="color: #e03e2d;" title="19元190G流量卡" href="http://u6v.cn/5yLX7Z" target="_blank" rel="noopener">免费领取,包邮到家 19元190G流量卡</a></span>
                    </div>
                </form>
            </div>
        </div>

        <!-- 搜索结果展示 -->
        <?php
        if (isset($_GET['q'])) {
            $q = urlencode($_GET['q']);
            $url = "http://sos.flymetoo.cn/search.php?q=$q";
            $result = @file_get_contents($url);
            
            if ($result === FALSE) {
                echo "<div class='alert alert-danger mt-4' role='alert'>搜索失败，请稍后重试。</div>";
                error_log("Failed to fetch results from: $url");
            } else {
                $resultData = json_decode($result, true);
                if ($resultData === null) {
                    echo "<div class='alert alert-danger mt-4' role='alert'>JSON解析失败，请检查API返回。</div>";
                    error_log("Failed to decode JSON: " . json_last_error_msg());
                } else {
                    $databaseResults = $resultData['database'];
                    $api2Results = $resultData['api2'];

                    if ($databaseResults['success'] && $databaseResults['total'] > 0) {
                        echo "<div class='row row-cols-1 row-cols-md-2 g-1 mt-1'>";
                        foreach ($databaseResults['data'] as $item) {
                            echo "<div class='col mb-1'>";
                            echo "<div class='result-item'>";
                            echo "<strong>资源名称：</strong> " . htmlspecialchars(strip_tags($item['disk_name'])) . "<br>";
                            echo "<strong>更新时间：</strong> " . htmlspecialchars($item['update_time']) . "<br>";
                            echo "<strong>链接：</strong> <a href='" . htmlspecialchars($item['link']) . "' target='_blank'> ". htmlspecialchars($item['disk_type']) ."</a><br>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-warning mt-4' role='alert'>未找到相关资源</div>";
                    }

                    if ($api2Results['success'] && $api2Results['total'] > 0) {
                        echo "<div class='row row-cols-1 row-cols-md-2 g-1 mt-1'>";
                        foreach ($api2Results['data']['list'] as $item) {
                            echo "<div class='col mb-1'>";
                            echo "<div class='result-item'>";
                            echo "<strong>资源名称：</strong> " . htmlspecialchars(strip_tags($item['disk_name'])) . "<br>";
                            echo "<strong>更新时间：</strong> " . htmlspecialchars($item['update_time']) . "<br>";
                            echo "<strong>链接：</strong> <a href='" . htmlspecialchars($item['link']) . "' target='_blank'>" . htmlspecialchars($item['disk_type']) . "</a><br>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            }
        }
        ?>
    </main>

    <!-- 页脚 -->
    <footer class="mt-4 text-center">
        <hr>
        <p>邮箱 x_seele@foxmail.com 注明来意</p>
        <p>© 2024 网盘资源搜索器 <a href="https://github.com/aidup/api-so">源码下载</a></p>
    </footer>

</div>

</body>
</html>
