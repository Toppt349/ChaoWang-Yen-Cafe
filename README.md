<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Channel Walkie-Talkie</title>
    <script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: 'Courier New', Courier, monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .radio-case {
            width: 320px;
            background: linear-gradient(145deg, #2c2c2c, #111);
            border-radius: 35px;
            padding: 25px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.9), inset 0 0 15px rgba(255,255,255,0.05);
            border: 3px solid #333;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        .antenna {
            position: absolute;
            top: -140px;
            right: 50px;
            width: 18px;
            height: 140px;
            background: #000;
            border-radius: 10px 10px 0 0;
            border-left: 3px solid #444;
        }

        .screen {
            background-color: #708238; /* สีเขียวทหาร */
            color: #000;
            padding: 15px;
            border-radius: 8px;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.6);
            text-align: center;
            font-weight: bold;
            position: relative;
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .screen-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(255,255,255,0.1), transparent);
            pointer-events: none;
        }

        .my-id-container {
            background: rgba(0,0,0,0.1);
            padding: 5px;
            border-radius: 4px;
            font-size: 0.75em;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .my-id-container:hover {
            background: rgba(255,255,255,0.2);
        }

        .connection-list {
            font-size: 0.7em;
            text-align: left;
            overflow-y: auto;
            height: 50px;
            border-top: 1px solid rgba(0,0,0,0.2);
            padding-top: 5px;
        }

        .status-large {
            font-size: 1.5em;
            text-transform: uppercase;
            text-shadow: 1px 1px 0 rgba(255,255,255,0.2);
        }

        .controls {
            display: flex;
            gap: 5px;
        }

        input {
            flex: 1;
            padding: 10px;
            background: #000;
            border: 1px solid #444;
            color: #0f0;
            font-family: inherit;
            border-radius: 5px;
            outline: none;
        }

        button.btn-add {
            background: #333;
            color: white;
            border: 1px solid #555;
            width: 40px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.1s;
        }
        button.btn-add:active { background: #555; }

        .ptt-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .ptt-btn {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: radial-gradient(circle, #ff4444, #cc0000);
            border: 5px solid #555;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            cursor: pointer;
            box-shadow: 0 8px 15px rgba(0,0,0,0.6), inset 0 5px 10px rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: transform 0.1s, background 0.1s;
        }

        .ptt-btn:active:not(.disabled) {
            transform: scale(0.95);
            background: radial-gradient(circle, #cc0000, #990000);
        }

        .ptt-btn.disabled {
            background: #444;
            color: #888;
            cursor: not-allowed;
            border-color: #333;
            box-shadow: none;
        }

        .speaker-grill {
            height: 40px;
            background-image: radial-gradient(#000 20%, transparent 20%);
            background-size: 6px 6px;
            margin-top: 15px;
            opacity: 0.6;
            border-radius: 5px;
            border: 1px solid #333;
        }

        /* Tooltip สำหรับตอน Copy */
        .tooltip {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #0f0;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.5s;
        }
    </style>
</head>
<body>

    <div class="tooltip" id="copy-tooltip">COPIED ID!</div>

    <div class="radio-case">
        <div class="antenna"></div>
        
        <div class="screen">
            <div class="screen-overlay"></div>
            
            <div class="my-id-container" onclick="copyMyId()" title="Click to Copy">
                <span id="my-id-display">Generating ID...</span>
                <i class="fas fa-copy"></i>
            </div>

            <div id="status-text" class="status-large">OFFLINE</div>

            <div class="connection-list" id="conn-list">
                Wait for connection...
            </div>
        </div>

        <div class="controls">
            <input type="text" id="remote-id-input" placeholder="Enter Friend ID">
            <button class="btn-add" onclick="connectToPeer()"><i class="fas fa-plus"></i></button>
        </div>

        <div class="speaker-grill"></div>

        <div class="ptt-container">
            <div id="ptt-btn" class="ptt-btn disabled">PUSH<br>TO<br>TALK</div>
        </div>
    </div>

    <div id="audio-container"></div>

    <script>
        let peer = null;
        let localStream = null;
        let activeCalls = []; // เก็บรายการสายที่เชื่อมต่อทั้งหมด

        const myIdDisplay = document.getElementById('my-id-display');
        const statusText = document.getElementById('status-text');
        const pttBtn = document.getElementById('ptt-btn');
        const connList = document.getElementById('conn-list');
        const audioContainer = document.getElementById('audio-container');
        const copyTooltip = document.getElementById('copy-tooltip');

        async function initRadio() {
            try {
                // ขออนุญาตใช้ไมค์
                localStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                localStream.getAudioTracks()[0].enabled = false; // Mute ไว้ก่อน

                // สร้าง Peer
                peer = new Peer();

                peer.on('open', (id) => {
                    myIdDisplay.innerText = "MY ID: " + id;
                    statusText.innerText = "STANDBY";
                    pttBtn.classList.remove('disabled');
                });

                // เมื่อมีคนอื่นโทรเข้ามา (รับสายอัตโนมัติ)
                peer.on('call', (call) => {
                    console.log("Incoming call from: " + call.peer);
                    handleCall(call);
                });

                peer.on('error', (err) => {
                    console.error(err);
                    alert("Error: " + err.type);
                });

            } catch (err) {
                alert("Please allow microphone access!");
                console.error(err);
            }
        }

        // ฟังก์ชันจัดการการเชื่อมต่อ (ใช้ทั้งตอนโทรออก และรับสาย)
        function handleCall(call) {
            // รับสายและส่ง stream ของเราไป (ซึ่ง mute อยู่)
            call.answer(localStream);
            
            // เก็บ call ลง array เพื่อจัดการภายหลัง
            activeCalls.push(call);
            updateConnectionList();

            // เมื่อได้รับเสียงจากอีกฝั่ง
            call.on('stream', (remoteStream) => {
                // สร้าง <audio> ใหม่สำหรับเพื่อนคนนี้โดยเฉพาะ
                const audio = document.createElement('audio');
                audio.srcObject = remoteStream;
                audio.autoplay = true;
                audio.id = `audio-${call.peer}`; // ตั้ง ID ให้จัดการง่าย
                audioContainer.appendChild(audio);

                // ถ้ามีเสียงเข้ามา แสดงสถานะ Receiving
                statusText.innerText = "RECEIVING";
                setTimeout(() => { statusText.innerText = "READY"; }, 2000);
            });

            call.on('close', () => {
                // ลบเสียงและรายชื่อเมื่อวางสาย
                const audio = document.getElementById(`audio-${call.peer}`);
                if (audio) audio.remove();
                activeCalls = activeCalls.filter(c => c !== call);
                updateConnectionList();
            });
        }

        // โทรหาเพื่อน
        function connectToPeer() {
            const remoteId = document.getElementById('remote-id-input').value.trim();
            if (!remoteId) return alert("Please enter an ID");
            if (activeCalls.find(c => c.peer === remoteId)) return alert("Already connected to this user!");

            const call = peer.call(remoteId, localStream);
            handleCall(call);
            document.getElementById('remote-id-input').value = ''; // ล้างช่อง
        }

        // อัปเดตรายชื่อคนในห้องบนหน้าจอ
        function updateConnectionList() {
            if (activeCalls.length === 0) {
                connList.innerHTML = "No connections.";
                statusText.innerText = "STANDBY";
            } else {
                connList.innerHTML = activeCalls.map(c => `<div><i class="fas fa-user"></i> ${c.peer.substr(0,8)}...</div>`).join('');
                statusText.innerText = "READY";
            }
        }

        // --- ระบบ PTT (Push To Talk) ---
        function startTalk() {
            if (!localStream) return;
            localStream.getAudioTracks()[0].enabled = true; // เปิดไมค์
            statusText.innerText = "TRANSMIT";
            statusText.style.color = "#8b0000"; // เปลี่ยนสีตัวหนังสือในจอ
            pttBtn.style.transform = "scale(0.95)";
        }

        function stopTalk() {
            if (!localStream) return;
            localStream.getAudioTracks()[0].enabled = false; // ปิดไมค์
            statusText.innerText = "READY";
            statusText.style.color = "black";
            pttBtn.style.transform = "scale(1)";
        }

        // Mouse Events
        pttBtn.addEventListener('mousedown', startTalk);
        window.addEventListener('mouseup', stopTalk); // ใช้ window เพื่อกันปุ่มค้างถ้าย้ายเมาส์ออก

        // Touch Events (มือถือ)
        pttBtn.addEventListener('touchstart', (e) => { e.preventDefault(); startTalk(); });
        pttBtn.addEventListener('touchend', (e) => { e.preventDefault(); stopTalk(); });


        // --- ฟังก์ชันเสริม: Copy ID ---
        function copyMyId() {
            const idText = myIdDisplay.innerText.replace("MY ID: ", "");
            navigator.clipboard.writeText(idText).then(() => {
                copyTooltip.style.opacity = 1;
                setTimeout(() => copyTooltip.style.opacity = 0, 2000);
            });
        }

        initRadio();
    </script>
</body>
</html>
