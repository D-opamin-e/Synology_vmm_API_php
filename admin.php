<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// 사용자 이름과 비밀번호
$username = 'Synology_id';
$password = 'Synology_pw';

// Synology NAS의 IP 주소나 도메인 이름을 입력해주세요.
$apiUrl = 'http://synology_nas_ip_or_domain:5000/webapi/entry.cgi';

// 로그인 요청을 보낼 API 경로
$loginPath = '/webapi/auth.cgi?api=SYNO.API.Auth&method=login&version=3&account=' . urlencode($username) . '&passwd=' . urlencode($password) . '&session=SurveillanceStation&format=sid';

// HTTPS 요청 옵션
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-Type: application/json',
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
    $_SESSION['sid'] = $sid;
    // SID 값을 출력하는 팝업 창 띄우기
    // echo "<script>alert('SID: $sid');</script>";
    // echo "SID: $sid<br>";
} else {
    // 로그인 실패한 경우
    echo '로그인 실패: ' . $loginResult['error']['code'];
}

// VM 정보 요청 보낼 API 경로
$vmpath = '/webapi/entry.cgi?api=SYNO.Virtualization.API.Guest&version=1&method=list&additional=true&_sid=' . $sid;

// 정보 요청 
$vmurl = $apiUrl . $vmpath;
$vmResult = file_get_contents($vmurl, false, stream_context_create($options));
$vmResult = json_decode($vmResult, true);

// 결과를 XML 파일로 저장
$vmResultXml = new SimpleXMLElement('<vm_list/>');
foreach ($vmResult['data']['guests'] as $vm) {
    $virtualmachine = $vmResultXml->addChild('virtualmachine');
    $virtualmachine->addChild('guest_name', $vm['guest_name']);
    $virtualmachine->addChild('vcpu_num', $vm['vcpu_num']);
    $virtualmachine->addChild('vram_size', $vm['vram_size']);
    $virtualmachine->addChild('status', $vm['status']);
}
$vmResultXml->asXML(__DIR__ . '/vm_result.xml');

// XML 파일 불러오기
$xml = simplexml_load_file(__DIR__ . '/vm_result.xml');

// HTML 테이블 생성
echo '<table>';
echo '<tr><th>이름</th><th>CPU 코어 개수</th><th>메모리</th><th>상태</th></tr>';
foreach ($xml->virtualmachine as $vm) {
echo '<tr>';
echo '<td>' . (isset($vm->guest_name) ? $vm->guest_name : '-') . '</td>';
echo '<td>' . (isset($vm->vcpu_num) ? $vm->vcpu_num : '-') . '</td>';
echo '<td>' . (isset($vm->vram_size) ? $vm->vram_size : '-') . '</td>';
echo '<td>' . (isset($vm->status) ? $vm->status : '-') . '</td>';
echo '</tr>';
}
echo '</table>';
// 세션 종료
session_destroy();
?>
