<style>
.header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 60px;
    background-color: #0033a0;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 1000;
}
.header-left h2 { font-size: 24px; font-weight: 600; color: white; margin: 0; }
.header-left h2 .medi { color: white; }
.header-left h2 .book { color: #4fc3f7; }
.header-center { position: absolute; left: 50%; transform: translateX(-50%); }
.header-center h1 { font-size: 20px; font-weight: 600; color: white; margin: 0; }
.header-right { display: flex; align-items: center; gap: 15px; }
.user-avatar { width: 35px; height: 35px; border-radius: 50%; background-color: white; color: #0033a0; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; }
.user-info { display: flex; align-items: center; gap: 10px; }
.user-info span { color: white; font-size: 14px; }
.logout-btn { background-color: rgba(255,255,255,0.2); color: white; padding: 8px 20px; border: 1px solid white; border-radius: 5px; text-decoration: none; font-size: 14px; transition: all 0.3s; }
.logout-btn:hover { background-color: rgba(255,255,255,0.35); }
body { padding-top: 60px; }
</style>

<div class="header">
    <div class="header-left">
        <h2><span class="medi">Medi</span><span class="book">Book</span></h2>
    </div>
    <div class="header-center">
        <h1>Receptionist Panel</h1>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr(isset($_SESSION['name']) ? $_SESSION['name'] : 'R', 0, 1)); ?>
            </div>
            <span><?php echo htmlspecialchars(isset($_SESSION['name']) ? $_SESSION['name'] : 'Receptionist'); ?></span>
        </div>
        <a href="../../controllers/receptionistLogoutController.php" class="logout-btn">Logout</a>
    </div>
</div>