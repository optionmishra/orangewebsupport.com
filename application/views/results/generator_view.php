<?php
// application/views/results/generator_view.php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Result Generator</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 0;
      padding: 20px;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #333;
      text-align: center;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #45a049;
    }

    .message {
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 4px;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .validation-error {
      color: #721c24;
      font-size: 0.9em;
      margin-top: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Student Result Generator</h1>

    <?php if (!empty($result_message)): ?>
      <div class="message <?php echo $status; ?>">
        <?php echo $result_message; ?>
      </div>
    <?php endif; ?>

    <?php echo form_open('ResultGenerator'); ?>
    <div class="form-group">
      <label for="class">Class:</label>
      <input type="number" id="class" name="class" value="<?php echo set_value('class', $class); ?>" required>
      <?php echo form_error('class', '<div class="validation-error">', '</div>'); ?>
    </div>

    <div class="form-group">
      <label for="section">Section:</label>
      <input type="number" id="section" name="section" value="<?php echo set_value('section', $section); ?>" required>
      <?php echo form_error('section', '<div class="validation-error">', '</div>'); ?>
    </div>

    <div class="form-group">
      <label for="student_code">Student Code:</label>
      <input type="text" id="student_code" name="student_code" value="<?php echo set_value('student_code', $student_code); ?>" required>
      <?php echo form_error('student_code', '<div class="validation-error">', '</div>'); ?>
    </div>

    <div class="form-group">
      <label for="assign_id">assign_id:</label>
      <input type="text" id="assign_id" name="assign_id" value="<?php echo set_value('assign_id', $assign_id); ?>" required>
      <?php echo form_error('assign_id', '<div class="validation-error">', '</div>'); ?>
    </div>

    <button type="submit">Generate Result</button>
    <?php echo form_close(); ?>

    <?php if (isset($results) && $results): ?>
      <h2>Student Result</h2>
      <table>
        <tr>
          <th>Student ID</th>
          <td><?php echo $results->student_id; ?></td>
        </tr>
        <tr>
          <th>Class</th>
          <td><?php echo $results->class; ?></td>
        </tr>
        <tr>
          <th>Section</th>
          <td><?php echo $results->section; ?></td>
        </tr>
        <tr>
          <th>Student Code</th>
          <td><?php echo $results->stu_teacher_code; ?></td>
        </tr>
        <tr>
          <th>Objective 1</th>
          <td>
            <?php echo $results->obj_1 ? $results->obj_1 : 'N/A'; ?>
            <?php if ($results->obj_1_dt): ?>
              <small>(Date: <?php echo $results->obj_1_dt; ?>)</small>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Objective 2</th>
          <td>
            <?php echo $results->obj_2 ? $results->obj_2 : 'N/A'; ?>
            <?php if ($results->obj_2_dt): ?>
              <small>(Date: <?php echo $results->obj_2_dt; ?>)</small>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Objective 3</th>
          <td>
            <?php echo $results->obj_3 ? $results->obj_3 : 'N/A'; ?>
            <?php if ($results->obj_3_dt): ?>
              <small>(Date: <?php echo $results->obj_3_dt; ?>)</small>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Objective 4</th>
          <td>
            <?php echo $results->obj_4 ? $results->obj_4 : 'N/A'; ?>
            <?php if ($results->obj_4_dt): ?>
              <small>(Date: <?php echo $results->obj_4_dt; ?>)</small>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    <?php endif; ?>
  </div>
</body>

</html>