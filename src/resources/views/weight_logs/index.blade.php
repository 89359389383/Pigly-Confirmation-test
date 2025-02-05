<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>PiGLy - Weight Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/index.css') }}">
</head>

<body>
    <header class="header">
        <div class="logo">PiGLy</div>
        <div class="header-buttons">
            <button class="header-btn">目標体重設定</button>
            <button class="header-btn">ログアウト</button>
        </div>
    </header>

    <main class="main-content">
        <div class="metrics">
            <div class="metric-box">
                <div class="metric-label">目標体重</div>
                <div class="metric-value">
                    {{ $weightTarget ? $weightTarget->target_weight : '未設定' }}<span class="metric-unit">kg</span>
                </div>
            </div>
            <div class="metric-box">
                <div class="metric-label">目標まで</div>
                <div class="metric-value">-1.5<span class="metric-unit">kg</span></div>
            </div>
            <div class="metric-box">
                <div class="metric-label">最新体重</div>
                <div class="metric-value">46.5<span class="metric-unit">kg</span></div>
            </div>
        </div>

        <div class="controls">
            <div class="date-range">
                <input type="text" class="date-input" placeholder="年/月/日">
                <span>~</span>
                <input type="text" class="date-input" placeholder="年/月/日">
                <button class="search-btn">検索</button>
            </div>
            <button class="add-data-btn">データ追加</button>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>体重</th>
                    <th>食事摂取カロリー</th>
                    <th>運動時間</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2023/11/26</td>
                    <td>46.5kg</td>
                    <td>1200cal</td>
                    <td>00:15</td>
                    <td><button class="edit-btn">✎</button></td>
                </tr>
                <tr>
                    <td>2023/11/25</td>
                    <td>46.5kg</td>
                    <td>1200cal</td>
                    <td>00:15</td>
                    <td><button class="edit-btn">✎</button></td>
                </tr>
                <tr>
                    <td>2023/11/24</td>
                    <td>46.5kg</td>
                    <td>1200cal</td>
                    <td>00:15</td>
                    <td><button class="edit-btn">✎</button></td>
                </tr>
                <tr>
                    <td>2023/11/23</td>
                    <td>46.5kg</td>
                    <td>1200cal</td>
                    <td>00:15</td>
                    <td><button class="edit-btn">✎</button></td>
                </tr>
                <tr>
                    <td>2023/11/22</td>
                    <td>46.5kg</td>
                    <td>1200cal</td>
                    <td>00:15</td>
                    <td><button class="edit-btn">✎</button></td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <button class="page-btn">＜</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">＞</button>
        </div>
    </main>
</body>

</html>