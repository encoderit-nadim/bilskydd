<?php
function add_preloader_styles()
{
    ?>
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 16px;
            color: #333;
        }
    </style>
    <?php
}
add_action('wp_head', 'add_preloader_styles');




function add_preloader_script()
{
    ?>
    <script>
        window.addEventListener('load', function () {
            const preloader = document.getElementById('preloader');
            setTimeout(function () {
                preloader.classList.add('hidden');
                setTimeout(function () {
                    preloader.style.display = 'none';
                }, 400);
            }, 200);
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_preloader_script');