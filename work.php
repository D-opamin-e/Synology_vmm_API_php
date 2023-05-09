<?php
  session_start();

  if (!isset($_SESSION['id'])) {
      header('Location: login.php');
      exit();
  }
$vmName = $_POST['vmName'];
$selectedOption = $_POST['selectedOption'];

// 사용자 이름과 비밀번호
$username = 'Synology_ID';
$password = 'Synology_PW';

// 인증 정보를 Base64로 인코딩하여 헤더에 포함
$auth = 'Basic ' . base64_encode($username . ':' . $password);
$headers = array('Authorization: ' . $auth);

// Synology NAS의 IP 주소나 도메인 이름을 입력해주세요.
$apiUrl = 'http://Synology_ip_or_domain:5000/webapi/entry.cgi';

// 로그인 요청을 보낼 API 경로
$loginPath = '/webapi/auth.cgi?api=SYNO.API.Auth&method=login&version=3&account=' . urlencode($username) . '&passwd=' . urlencode($password) . '&session=SurveillanceStation&format=sid';

// HTTPS 요청 옵션
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => implode("\r\n", $headers) . "\r\n",
        'ignore_errors' => true
    ),
    'ssl' => array(
        'protocol' => 'TLSv1.2'
    )
);

// 로그인 요청 보내기
$loginUrl = $apiUrl . $loginPath;
$loginResult = file_get_contents($loginUrl, false, stream_context_create($options));
$loginResult = json_decode($loginResult, true);

// 로그인이 성공적으로 수행되었다면 SID 값을 추출합니다.
if ($loginResult['success']) {
    $sid = $loginResult['data']['sid'];
    $_SESSION['sid'] = $loginResult['data']['sid'];

    // API 호출 시 필요한 매개변수들을 배열로 저장합니다.
    $params = array(
        'api' => 'SYNO.Virtualization.API.Guest.Action',
        'version' => 1,
        'guest_name' => $vmName,
        '_sid' => $sid
    );

    // 선택한 작업에 따라 API 호출 시 필요한 매개변수를 지정합니다.
    if ($selectedOption == 1) {
        $params['method'] = 'poweron';
    } elseif ($selectedOption == 2) {
        $params['method'] = 'shutdown';
    } elseif ($selectedOption == 3) {
        $params['method'] = 'poweroff';
    } else {
        echo '잘못된 입력입니다.';
        exit(1);
    }

    // API 호출을 위한 cURL 옵션을 설정합니다.
    $curlOptions = array(
        CURLOPT_URL => $apiUrl . '?' . http_build_query($params),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $auth
        )
    );

    // cURL을 사용하여 API 호출을 수행합니다.
    $curl = curl_init();
    curl_setopt_array($curl, $curlOptions);
    $response = curl_exec($curl);
    curl_close($curl);

   // API 호출 결과를 검사하여 작업이 성공적으로 완료되었는지 판단합니다.
$result = json_decode($response, true);
if ($result && $result['success']) {
    // 작업이 성공적으로 완료된 경우
    echo '<script>alert("작업이 성공적으로 마쳤습니다.");</script>';
} else {
    // 작업이 실패한 경우
    echo '<script>alert("작업을 실패했습니다.");</script>';
}
// 이전 페이지로 돌아가기
echo '<script>history.go(-1);</script>';
} else {
    // 로그인 실패한 경우
    echo '로그인 실패: ' . $loginResult['error']['code'];
    }