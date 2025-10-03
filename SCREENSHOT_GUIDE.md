# 📸 Screenshot Guide for Portfolio

## 필수 스크린샷 5장

### 1. admin-settings.png
**찍을 화면:**
```
http://localhost:8080/wp-admin/options-general.php?page=wplp
```
**포함할 내용:**
- Settings → Learning Portal 메뉴
- Default Reminder Message 입력 필드
- Nonce 보안이 적용된 폼
- Save 버튼

**강조 포인트:**
- WordPress 관리자 페이지 커스터마이징
- 보안 구현 (nonce)

---

### 2. course-edit.png
**찍을 화면:**
```
http://localhost:8080/wp-admin/post.php?post=[강좌ID]&action=edit
```
(또는 Courses → Edit 아무거나 클릭)

**포함할 내용:**
- 강좌 제목/내용 편집 화면
- **우측 사이드바: "Course Download Link" 메타박스** (핵심!)
- Download URL 입력 필드
- Publish 버튼

**강조 포인트:**
- Custom Meta Box 구현
- 확장 가능한 데이터 구조

---

### 3. course-list-search.png
**찍을 화면:**
```
http://localhost:8080/[데모페이지URL]
```
(방금 만든 데모 페이지)

**포함할 내용:**
- "Available Courses" 제목
- **검색 박스** (둥근 테두리, 파란색 포커스)
- **강좌 목록** (카드 형태, 초록 테두리)
- **다운로드 버튼** (초록색 둥근 버튼, "⬇ Download")

**강조 포인트:**
- 실시간 검색 기능
- 반응형 디자인
- Fear Free 스타일 적용

**추가 액션:**
- 검색 박스에 뭔가 입력해서 필터링 동작하는 거 보여주기

---

### 4. pet-reminder.png
**찍을 화면:**
```
같은 데모 페이지에서 아래로 스크롤
```

**포함할 내용:**
- "Important Reminder" 제목
- **리마인더 박스** (파란 테두리, 💡 아이콘, 밝은 파란 배경)
- 커스텀 메시지 내용

**강조 포인트:**
- Shortcode 재사용성
- CSS 커스터마이징

---

### 5. react-progress.png
**찍을 화면:**
```
같은 데모 페이지 맨 아래
```

**포함할 내용:**
- "Your Progress" 제목
- **React 진행률 위젯** (파란 바, 그림자, "65% complete")
- 설명 텍스트

**강조 포인트:**
- React 18 통합
- WordPress + 모던 JS 스택

---

## 🎨 스크린샷 팁

### 해상도:
- **최소:** 1280x800
- **권장:** 1920x1080 (Full HD)

### 브라우저:
- Chrome/Safari (깔끔한 UI)
- 개발자 도구는 닫기
- 북마크바 숨기기 (Cmd+Shift+B)

### 편집:
- 민감한 정보 가리기 (아이디, 비번 등)
- 화살표/하이라이트 추가 (선택사항)
- 파일명 정확히: `admin-settings.png` (소문자, 하이픈)

---

## 📂 저장 위치

```
fearfree-learning-demo/
├── screenshots/
│   ├── admin-settings.png
│   ├── course-edit.png
│   ├── course-list-search.png
│   ├── pet-reminder.png
│   └── react-progress.png
└── README.md (이 이미지들을 링크)
```

---

## ✅ 체크리스트

- [ ] 1. admin-settings.png (Settings 페이지)
- [ ] 2. course-edit.png (메타박스 보이게)
- [ ] 3. course-list-search.png (검색+목록+다운로드)
- [ ] 4. pet-reminder.png (리마인더 박스)
- [ ] 5. react-progress.png (진행률 위젯)
- [ ] screenshots 폴더 생성
- [ ] 모든 이미지 PNG 포맷
- [ ] README.md에 이미지 링크 추가

---

## 🚀 다음 단계

스크린샷 완료 후:
1. Git 저장소 초기화
2. GitHub에 업로드
3. README 업데이트 (이미지 포함)
4. LinkedIn/이력서에 프로젝트 링크 추가
