
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Video Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: white;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .video-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .video-box {
            background: #202020;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .video-box:hover {
            transform: scale(1.05);
        }

        iframe {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            border: none;
        }

        .video-title {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
    </style>

    <h1>🎥 My Video Gallery</h1>

    <div class="video-container">
        <div class="video-box">
            <iframe src="https://drive.google.com/file/d/1v_PcupEWHj-DZ1rC7CvMG0xh2Gge_TPU/preview" allow="autoplay"></iframe>
            <div class="video-title">Video 1</div>
        </div>

        <div class="video-box">
            <iframe src="https://drive.google.com/file/d/1zpBDTn28jPtmZH59HgNXS-jToSoB2oOu/preview" allow="autoplay"></iframe>
            <div class="video-title">Video 2</div>
        </div>

        <div class="video-box">
            <iframe src="https://drive.google.com/file/d/1uBgqAHaDKggp6AyOXQ9oh0sZz6t18btH/preview" allow="autoplay"></iframe>
            <div class="video-title">Video 3</div>
        </div>

        <div class="video-box">
            <iframe src="https://drive.google.com/file/d/1AiLE5aP4VqSOMEUPcGYzrbbedEnnUxBa/preview" allow="autoplay"></iframe>
            <div class="video-title">Video 4</div>
        </div>
    </div>


