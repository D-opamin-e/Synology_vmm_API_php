<!DOCTYPE html>
<html>
  <?php
  session_start();

  if (!isset($_SESSION['id'])) {
      header('Location: login.php');
      exit();
  }
  ?>
  <head>
    <meta charset="utf-8">
    <title>VM Admin</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#executeBtn").click(function() {
				var vmName = $("#vmName").val();
				var selectedOption = $("#selectedOption").val();
				$.ajax({
					type: "POST",
					url: "work.php",
					data: {
						vmName: vmName,
						selectedOption: selectedOption
					},
					success: function(response) {
						if (response.success) {
							alert("작업이 성공적으로 마쳤습니다.");
						} else {
							alert("작업을 실패했습니다.");
						}
					},
					error: function() {
						alert("작업을 실패했습니다.");
					}
				});
			});
		});
	</script>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
      }
      header {
    background-color: #333;
    color: #fff;
    padding: 20px;
  }

  h1 {
    margin: 0;
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  label {
    display: inline-block;
    width: 120px;
    margin-bottom: 10px;
  }

  input[type="text"],
  select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    width: 100%;
    margin-bottom: 20px;
  }

  button[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }

  button[type="submit"]:hover {
    background-color: #45a049;
  }

  .error {
    color: red;
    margin-top: 10px;
  }
</style>
</head>
<body>
  <header>
    <h1>VM 작업</h1>
  </header>
  <div class="container">
    <form method="post" action="work.php">
      <label for="vmName">VM 이름</label>
      <input type="text" id="vmName" name="vmName" required><br>
      <label for="selectedOption">작업 선택</label>
      <select id="selectedOption" name="selectedOption" required>
        <option value="" selected disabled hidden>선택하세요</option>
        <option value="1">부팅</option>
        <option value="2">종료</option>
        <option value="3">강제종료</option>
      </select><br>
  
      <button type="submit">작업 실행</button>
    </form>
  </div>
</body>
</html> 

  