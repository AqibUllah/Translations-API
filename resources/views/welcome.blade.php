<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translations API - Simplify Language Translation</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 800px;
            padding: 20px;
        }

        h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 30px;
            animation: fadeIn 3s ease-in-out;
        }

        .cta-button {
            background: #ff6f61;
            color: #fff;
            padding: 15px 30px;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            animation: fadeIn 4s ease-in-out;
        }

        .cta-button:hover {
            background: #ff4a3d;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            p {
                font-size: 1rem;
            }

            .cta-button {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Translations API</h1>
    <p>
        Welcome to the <strong>Translations API</strong> – your ultimate solution for seamless language translation.
        Our API empowers developers to integrate real-time translation capabilities into their applications with ease.
        Whether you're building a global app or need multilingual support, our API is here to simplify your workflow.
    </p>
    <button class="cta-button" onclick="window.location.href='/api/documentation'">Get Started</button>
</div>
</body>
</html>
