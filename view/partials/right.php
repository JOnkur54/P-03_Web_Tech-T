<div class="right">

<div class="card">
  <h3>Patient Panel</h3>
  <p>Welcome <?php echo htmlspecialchars($_SESSION['patient_name'] ?? 'Patient'); ?></p>
</div>

<div class="card">
  <h3>Filter by Specialization</h3>
  <form method="GET" action="viewAvailableAppointments.php" style="margin: 0;">
    <?php
      $selectedSpec = isset($_GET['specialization']) ? (int)$_GET['specialization'] : 0;
    ?>

    <label for="specialization" style="display:block;margin-top:10px;">Specialization</label>
    <select name="specialization" id="specialization" style="width:100%;">
      <option value="0" <?php echo $selectedSpec === 0 ? 'selected' : ''; ?>>All</option>
      <?php
        // We don't fetch specialization list from DB here; keep UI simple.
        // User can still filter because doctors dropdown is populated from selected specialization server-side.
        // If you later add a getSpecializations() model method, this block can be upgraded.
      ?>
      <?php
        // NOTE: These IDs must match your DB rows from `specializations` table.
        // Update them if your specialization IDs are different.
      ?>
      <option value="1" <?php echo $selectedSpec === 1 ? 'selected' : ''; ?>>General Medicine</option>
      <option value="2" <?php echo $selectedSpec === 2 ? 'selected' : ''; ?>>Cardiology</option>
      <option value="3" <?php echo $selectedSpec === 3 ? 'selected' : ''; ?>>Dermatology</option>
      <option value="4" <?php echo $selectedSpec === 4 ? 'selected' : ''; ?>>Orthopedics</option>
    </select>

    <button type="submit" style="margin-top:12px;">Apply</button>
  </form>
</div>

<div class="card">
  <h3>Emergency</h3>
  <p>Call : 999</p>
</div>

</div>
