<div class="right">

<div class="card">
<h3>Patient Panel</h3>
<p>Welcome <?php echo htmlspecialchars($_SESSION['patient_name'] ?? 'Patient'); ?></p>
</div>

<div class="card">
<h3>Emergency</h3>
<p>Call : 999</p>
</div>

</div>


<style>
.right  {
    width: 25%;
    background-color: #f8f9fa;
    padding: 20px;
    box-sizing: border-box;
}
.card {
    background-color: #ffffff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card h3 {
    margin-top: 0;
}
.card p {
    margin-bottom: 0;
}
</style>

