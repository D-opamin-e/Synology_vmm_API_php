# Synology_vmm_API_php

index.php -> Synology VMM에서 작업을 진행할 가상머신의 이름 혹은 부팅, 종료, 강제종료를 지정할 수 있습니다.
            만약) 세션이 없는 경우 login.php로 이동합니다.
            
login.php -> ID,PW를 입력받고, DB에서 확인을 합니다.
            만약, admin이 1인 유저라면 ==> admin.php로 이동시키며, admin이 0인 유저라면 ==> index.php로 이동합니다.
            
work.php -> 처리 페이지입니다.
            매번 변경되는 SID 값을 Synology VMM API에 요청을 하여 가져오고, 가져온 SID 값으로 work.php에서 처리를 합니다.
            
admin.php -> admin 페이지입니다.
             본 페이지에서는 생성이 되어있는 VMM의 리스트를 볼 수 있으며, 이름, 할당된 코어 개수, 할당된 램, 현재 상태를 조회할 수 있습니다.
             SimpleXML 모듈을 사용했습니다.
             
===================ENG==================
             
index.php -> You can specify the name or boot, shut down, or force shutdown of the virtual machine that you want to work with Synology VMM.
              If) there is no session, login.Go to php.

login.php -> ID, PW, and check in DB.
            If admin is 1, go to ==> admin.php; if admin is 0, go to ==> index.php.

Work.php -> Processing page.
            The SID value that changes every time is requested to the Synology VMM API to obtain it, and work.php processes it with the imported SID value.

admin.php -> admin page.
              Use this page to view a list of VMMs that have been created, and to view the name, number of cores assigned, assigned RAM, and current status.
              SimpleXML module used.
