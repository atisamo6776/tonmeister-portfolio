
<?php
// admin/includes/header.php
// Bu dosya tüm admin sayfalarının başında include edilecektir.

// Başlık belirlenmemişse varsayılan başlık
if (!isset($page_title)) {
    $page_title = "Yönetim Paneli";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Serkan Ölçer Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Ana sitenin stilini kullanabiliriz -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Admin paneli özel stilleri */
        body {
            background-color: var(--light-bg-color);
        }
        .admin-sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: var(--light-text-color);
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }
        .admin-sidebar h3 {
            color: var(--light-text-color);
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }
        .admin-sidebar ul li a {
            display: block;
            color: var(--light-text-color);
            padding: 15px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .admin-sidebar ul li a:hover,
        .admin-sidebar ul li a.active {
            background-color: var(--secondary-color);
        }
        .admin-main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .admin-header {
            background-color: var(--bg-color);
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .admin-header .welcome-text {
            font-weight: bold;
            color: var(--primary-color);
        }
        .admin-header .logout-btn {
            background-color: #dc3545; /* Kırmızı */
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .admin-header .logout-btn:hover {
            background-color: #c82333;
        }
        .admin-container h2 {
            margin-bottom: 25px;
            color: var(--primary-color);
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: var(--bg-color);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-card h3 {
            color: var(--secondary-color);
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 2em;
            font-weight: bold;
            color: var(--text-color);
        }
        .quick-actions ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .quick-actions ul li a.btn {
            padding: 10px 20px;
            font-size: 0.9em;
        }
        /* Form ve Tablo Genel Stilleri */
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .form-group input[type="submit"],
        .form-group .btn-action {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="submit"]:hover,
        .form-group .btn-action:hover {
            background-color: var(--secondary-color);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--bg-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .data-table th,
        .data-table td {
            border: 1px solid var(--border-color);
            padding: 12px 15px;
            text-align: left;
        }
        .data-table th {
            background-color: var(--light-bg-color);
            font-weight: bold;
            color: var(--primary-color);
        }
        .data-table tr:nth-child(even) {
            background-color: var(--light-bg-color);
        }
        .data-table tr:hover {
            background-color: #f0f0f0;
        }
        .data-table .action-buttons a {
            margin-right: 5px;
        }
        .data-table .btn-edit {
            background-color: #ffc107;
        }
        .data-table .btn-delete {
            background-color: #dc3545;
        }
        /* Dark Theme Admin Adjustments */
        body.dark-theme .admin-header {
            background-color: var(--dark-bg-color);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        body.dark-theme .admin-header .welcome-text {
            color: var(--accent-color);
        }
        body.dark-theme .admin-sidebar {
            background-color: var(--dark-bg-color);
        }
        body.dark-theme .admin-sidebar h3 {
            color: var(--accent-color);
        }
        body.dark-theme .admin-sidebar ul li a:hover,
        body.dark-theme .admin-sidebar ul li a.active {
            background-color: #444;
        }
        body.dark-theme .stat-card {
            background-color: var(--dark-bg-color);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        body.dark-theme .stat-card h3 {
            color: var(--accent-color);
        }
        body.dark-theme .stat-card p {
            color: var(--light-text-color);
        }
        body.dark-theme .form-group input[type="text"],
        body.dark-theme .form-group input[type="email"],
        body.dark-theme .form-group input[type="password"],
        body.dark-theme .form-group input[type="number"],
        body.dark-theme .form-group textarea,
        body.dark-theme .form-group select {
            background-color: #444;
            border-color: #666;
            color: var(--light-text-color);
        }
        body.dark-theme .data-table {
            background-color: var(--dark-bg-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        body.dark-theme .data-table th {
            background-color: #333;
            color: var(--accent-color);
        }
        body.dark-theme .data-table td {
            border-color: #444;
        }
        body.dark-theme .data-table tr:nth-child(even) {
            background-color: #333;
        }
        body.dark-theme .data-table tr:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h3>Serkan Ölçer Admin</h3>
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="services.php"><i class="fas fa-hand-holding-usd"></i> Hizmetler</a></li>
                <li><a href="projects.php"><i class="fas fa-project-diagram"></i> Projeler</a></li>
                <li><a href="gallery.php"><i class="fas fa-images"></i> Galeri</a></li>
                <li><a href="blog.php"><i class="fas fa-blog"></i> Blog Yazıları</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Mesajlar</a></li>
                <li><a href="sliders.php"><i class="fas fa-sliders-h"></i> Slider Yönetimi</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Kullanıcılar</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </div>
        <div class="admin-main-content">
            <div class="admin-header">
                <div class="welcome-text">Hoş geldiniz, <?php echo $_SESSION['username']; ?></div>
                <a href="logout.php" class="logout-btn">Çıkış Yap</a>
            </div>
