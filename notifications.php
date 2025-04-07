<html>
<head>
    <title>Side Notification Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header styling */
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        
        .header-icons {
            display: flex;
            gap: 20px;
        }
        
        .icon-button {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            position: relative;
        }
        
        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff3b30;
            color: white;
            font-size: 12px;
            font-weight: bold;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Content area styling */
        .content {
            padding: 20px;
            flex-grow: 1;
        }
        
        /* Side notification panel */
        .notification-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background-color: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .notification-panel.show {
            transform: translateX(0);
        }
        
        .notification-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h2 {
            margin: 0;
            font-size: 18px;
        }
        
        .close-button {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .notification-list {
            flex-grow: 1;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-item:hover {
            background-color: #f9f9f9;
        }
        
        .message-info {
            display: flex;
            flex-direction: column;
        }
        
        .sender-name {
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .message-count {
            color: #666;
            font-size: 13px;
        }
        
        .chat-button {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
        }
        
        .chat-button:hover {
            background-color: #0056b3;
        }
        
        .no-notifications {
            padding: 20px;
            text-align: center;
            color: #666;
        }
        
        /* Demo content styling */
        .demo-content {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Overlay for mobile */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .overlay.show {
            display: block;
        }
        
        @media (max-width: 768px) {
            .notification-panel {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Header bar with notification icon -->
    <div class="header">
        <h1>LoveConnect</h1>
        <div class="header-icons">
            <button class="icon-button" id="notification-button">
                🔔
                <span class="notification-count">6</span>
            </button>
            <button class="icon-button">👤</button>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="content">
        <div class="demo-content">
            <h2>Welcome to LoveConnect</h2>
            <p>This is a demo of how a side notification panel would look in your application. Click the bell icon in the header to see your notifications.</p>
            <p>This approach allows users to check their messages without navigating away from the current page, providing a smoother user experience.</p>
        </div>
    </div>
    
    <!-- Background overlay (visible on mobile when panel is open) -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Side notification panel -->
    <div class="notification-panel" id="notification-panel">
        <div class="notification-header">
            <h2>New Messages</h2>
            <button class="close-button" id="close-button">×</button>
        </div>
        <div class="notification-list">
            <!-- Notification items -->
            <div class="notification-item">
                <div class="message-info">
                    <span class="sender-name">Sarah Johnson</span>
                    <span class="message-count">3 new messages</span>
                </div>
                <a class="chat-button" href="#">Chat 💬</a>
            </div>
            
            <div class="notification-item">
                <div class="message-info">
                    <span class="sender-name">Michael Thompson</span>
                    <span class="message-count">1 new message</span>
                </div>
                <a class="chat-button" href="#">Chat 💬</a>
            </div>
            
            <div class="notification-item">
                <div class="message-info">
                    <span class="sender-name">Jessica Williams</span>
                    <span class="message-count">2 new messages</span>
                </div>
                <a class="chat-button" href="#">Chat 💬</a>
            </div>
            
            <!-- Alternative state when no notifications
            <div class="no-notifications">
                <p>No new messages 📭</p>
            </div>
            -->
        </div>
    </div>
    
    <script>
        // Toggle notification panel visibility
        const notificationButton = document.getElementById('notification-button');
        const closeButton = document.getElementById('close-button');
        const notificationPanel = document.getElementById('notification-panel');
        const overlay = document.getElementById('overlay');
        
        notificationButton.addEventListener('click', () => {
            notificationPanel.classList.add('show');
            overlay.classList.add('show');
        });
        
        closeButton.addEventListener('click', () => {
            notificationPanel.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        overlay.addEventListener('click', () => {
            notificationPanel.classList.remove('show');
            overlay.classList.remove('show');
        });
    </script>
</body>
</html>
