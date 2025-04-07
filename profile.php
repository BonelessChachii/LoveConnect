<html>
<head>
    <title>Your Profile - LoveConnect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .profile-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            width: 400px;
            max-width: 90%;
        }
        
        h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .profile-field {
            margin-bottom: 15px;
        }
        
        .field-label {
            font-weight: bold;
            color: #555;
            margin-right: 8px;
        }
        
        .field-value {
            color: #333;
        }
        
        .action-buttons {
            display: flex;
            margin-top: 20px;
            gap: 10px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 16px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            text-align: center;
            white-space: nowrap;
        }
        
        .btn-view {
            background-color: #4CAF50;
        }
        
        .btn-view:hover {
            background-color: #388E3C;
        }
        
        .btn-edit {
            background-color: #2196F3;
        }
        
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        
        .btn-logout {
            background-color: #f44336;
        }
        
        .btn-logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Welcome, John Doe!</h2>
        
        <div class="profile-field">
            <span class="field-label">Age:</span>
            <span class="field-value">32</span>
        </div>
        
        <div class="profile-field">
            <span class="field-label">Gender:</span>
            <span class="field-value">Male</span>
        </div>
        
        <div class="profile-field">
            <span class="field-label">Interests:</span>
            <span class="field-value">Hiking, photography, cooking, jazz music, classic novels</span>
        </div>
        
        <div class="action-buttons">
            <a href="#" class="btn btn-view">View Profile</a>
            <a href="#" class="btn btn-edit">Edit Profile</a>
            <a href="#" class="btn btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>
