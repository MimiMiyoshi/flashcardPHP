<?php
// APIキーを環境変数から取得するなどのセキュアな方法を推奨
$api_key = getenv('GEMINI_API_KEY') ?: ;

// セッション開始
session_start();

// CSRF対策のトークン生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// funcs.php を読み込む
require_once('funcs.php');

//2. DB接続します
$pdo = db_conn();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search</title>
    <link rel="stylesheet" href="css/style.css" />
    </head>

<body>
    <div id="register-screen" class="screen">
        <div id="background"></div>
        <div id="content">
            <h1>単語を検索しましょ！</h1>
            <div>
                <input 
                    type="text" 
                    id="search-box" 
                    placeholder="検索する単語を入力"
                    autocomplete="off"
                />
            </div>
            <div>
                <button id="search-btn">検索</button>
                <input type="hidden" id="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div id="search-results"></div>
            <div>
                <button id="goNotepad" onclick="location.href='flashcard.php'">単語帳を開く</button>
            </div>
        </div>
    </div>

    <script type="importmap">
    {
        "imports": {
            "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
    }
    </script>

    <script type="module">
        import { GoogleGenerativeAI } from "@google/generative-ai";

        const API_KEY = <?php echo json_encode($api_key); ?>;
        const genAI = new GoogleGenerativeAI(API_KEY);
        const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

        let isSearching = false;

        async function searchWord() {
            const searchBox = document.getElementById("search-box");
            const searchBtn = document.getElementById("search-btn");
            const resultsDiv = document.getElementById("search-results");
            const csrfToken = document.getElementById("csrf_token").value;
            const query = searchBox.value.trim();

            if (query === "") {
                showError("検索ボックスが空です。単語を入力してください。");
                return;
            }

            if (isSearching) {
                return;
            }

            try {
                isSearching = true;
                searchBtn.disabled = true;
                showLoading();

                // CSRFトークンをヘッダーに含める
                const result = await model.generateContent(
                    `"${query}" は、フランス語の単語もしくはフレーズです。
                    以下の形式で日本語で回答してください：
                    ①品詞
                    ②このフランス語の日本語における代表的な意味
                    
                    できるだけ簡潔に回答してください。`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                );

                const response = await result.response;
                const text = response.text();
                
                resultsDiv.innerHTML = `<p>${text}</p>`;

                // 検索履歴をサーバーに保存（オプション）
                await fetch('save_history.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        query: query,
                        result: text
                    })
                });

            } catch (error) {
                showError(`エラーが発生しました: ${error.message}`);
                console.error("検索エラー:", error);
            } finally {
                isSearching = false;
                searchBtn.disabled = false;
            }
        }

        function showError(message) {
            const resultsDiv = document.getElementById("search-results");
            resultsDiv.innerHTML = `<div class="error">${message}</div>`;
        }

        function showLoading() {
            const resultsDiv = document.getElementById("search-results");
            resultsDiv.innerHTML = '<div class="loading">検索中...</div>';
        }

        function createDot() {
            const background = document.getElementById("background");
            if (!background) return;

            const dot = document.createElement("div");
            dot.classList.add("dot");

            const size = Math.random() * 10 + 5;
            dot.style.width = `${size}px`;
            dot.style.height = `${size}px`;
            dot.style.left = `${Math.random() * 100}vw`;
            dot.style.animationDuration = `${Math.random() * 5 + 5}s`;

            background.appendChild(dot);

            setTimeout(() => {
                dot.remove();
            }, 10000);
        }

        // イベントリスナーの設定
        document.getElementById("search-btn").addEventListener("click", searchWord);
        
        document.getElementById("search-box").addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                searchWord();
            }
        });

        // 背景アニメーションの開始
        setInterval(createDot, 500);
    </script>
</body>
</html>
