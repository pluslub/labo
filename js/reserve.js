const GAS_WEB_APP_URL = 'https://script.google.com/macros/s/AKfycbz0wUKykeZyqBT4cf0OJXy1JXvKXMVt7Mw9a1xP0FVaO5sWSmiR8N7D5dOYb761ZNsh/exec';
const TIME_SLOTS = ['10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

let busyList = [];
let holidayList = [];
let maxBookingDays = 90;
let currentDate = new Date();

// --------------------------------------------------
// ページ読み込み時の処理（自動判別）
// --------------------------------------------------
window.addEventListener('DOMContentLoaded', () => {
  // カレンダー埋め込みページ（contact.html等）用処理
  if (document.getElementById('monthGrid')) {
    initCalendar();
  }

  // reserve-form.html 用処理
  if (document.getElementById('bookingForm')) {
    initForm();
  }
});

// --------------------------------------------------
// カレンダー（#reservation-calendar を含むページ）用関数
// --------------------------------------------------
async function initCalendar() {
  try {
    const res = await fetch(GAS_WEB_APP_URL);
    const data = await res.json();
    if (data.status === 'success') {
      busyList = data.busySlots;
      holidayList = data.holidays || [];
      maxBookingDays = data.maxBookingDays || 90;
      renderMonthCalendar();
      document.getElementById('loading').style.display = 'none';
      document.getElementById('calendar-area').style.display = 'block';
    }
  } catch (err) {
    document.getElementById('loading').innerText = 'データ取得エラー: ' + err.message;
  }
}

function renderMonthCalendar() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();

  document.getElementById('currentMonthStr').innerText = `${year}年 ${month + 1}月`;

  const grid = document.getElementById('monthGrid');
  grid.innerHTML = '';

  const dayNames = ['月', '火', '水', '木', '金', '土', '日'];
  dayNames.forEach(d => {
    const div = document.createElement('div');
    div.className = 'day-name';
    div.innerText = d;
    grid.appendChild(div);
  });

  let firstDay = new Date(year, month, 1).getDay() - 1;
  if (firstDay === -1) firstDay = 6;

  const lastDate = new Date(year, month + 1, 0).getDate();
  const today = new Date();
  today.setHours(0,0,0,0);
  const maxBookingDate = new Date(today.getTime() + maxBookingDays * 24 * 60 * 60 * 1000);

  for (let i = 0; i < firstDay; i++) {
    const emptyCell = document.createElement('div');
    emptyCell.className = 'day-cell disabled';
    grid.appendChild(emptyCell);
  }

  for (let date = 1; date <= lastDate; date++) {
    const targetDate = new Date(year, month, date);
    const dateStr = formatDate(targetDate);
    const isPast = targetDate < today;
    const isTooFar = targetDate > maxBookingDate;
    const rawDay = targetDate.getDay();
    const isWeekend = (rawDay === 0 || rawDay === 6); // 土曜日または日曜日
    const isHoliday = holidayList.includes(dateStr);  // 祝日判定

    // 土日祝または過去日の場合は disabled クラスを付与
    const isClosed = isPast || isTooFar || isWeekend || isHoliday;
    const cell = document.createElement('div');
    cell.className = `day-cell ${isClosed ? 'disabled' : ''}`;

    let availableSlots = [];
    if (!isClosed) {
      TIME_SLOTS.forEach(time => {
        if (!checkIsBusy(dateStr, time)) availableSlots.push(time);
      });
    }

    let statusHtml = '';
    let tooltipHtml = '';

    if (isClosed || availableSlots.length === 0) {
      const labelText = (isWeekend || isHoliday) ? '休み' : '✕';
      statusHtml = `<div class="status-area ng">${labelText}</div>`;
    } else {
      statusHtml = `<div class="status-area ok">◯</div>`;

      let slotItems = availableSlots.map(time =>
        `<div class="time-slot-item" onclick="reserveSlot('${dateStr}', '${time}')">◯ ${time}</div>`
      ).join('');

      tooltipHtml = `<div class="tooltip">${slotItems}</div>`;
    }

    cell.innerHTML = `
      <div class="date-num">${date}</div>
      ${statusHtml}
      ${tooltipHtml}
    `;

    // ★スマホタップ対応：予約可能なマスをタップした時の表示切替
    if (!isClosed && availableSlots.length > 0) {
      cell.addEventListener('click', (e) => {
        if (e.target.classList.contains('time-slot-item')) return;
        const wasActive = cell.classList.contains('active-cell');
        document.querySelectorAll('.day-cell.active-cell').forEach(c => c.classList.remove('active-cell'));

        if (!wasActive) {
          cell.classList.add('active-cell');
        }
      });
    }

    grid.appendChild(cell);
  }
}

function checkIsBusy(dateStr, timeStr) {
  const slotStart = new Date(`${dateStr}T${timeStr}:00+09:00`).getTime();
  const slotEnd = slotStart + (60 * 60 * 1000);

  return busyList.some(busy => {
    const busyStart = new Date(busy.start).getTime();
    const busyEnd = new Date(busy.end).getTime();
    return (slotStart < busyEnd && slotEnd > busyStart);
  });
}

function reserveSlot(date, time) {
  window.location.href = `reserve-form.html?date=${date}&time=${time}`;
}

function changeMonth(diff) {
  currentDate.setDate(1);
  currentDate.setMonth(currentDate.getMonth() + diff);
  renderMonthCalendar();
}

function formatDate(d) {
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// --------------------------------------------------
// フォーム（reserve-form.html）用関数
// --------------------------------------------------
function initForm() {
  const urlParams = new URLSearchParams(window.location.search);
  const date = urlParams.get('date');
  const time = urlParams.get('time');

  const dateInput = document.getElementById('date');
  const timeInput = document.getElementById('time');
  const displayDateText = document.getElementById('display-date-text');
  const displayTimeText = document.getElementById('display-time-text');

  if (date && time) {
    if (dateInput) dateInput.value = date;
    if (timeInput) timeInput.value = time;

    if (displayDateText) displayDateText.innerText = date;
    if (displayTimeText) displayTimeText.innerText = `${time}〜`;
  } else {
    if (displayDateText) displayDateText.innerText = '日時未選択';
    if (displayTimeText) displayTimeText.innerText = '日時未選択';
    const toConfirmBtn = document.getElementById('toConfirmBtn');
    if (toConfirmBtn) toConfirmBtn.disabled = true;
  }

  // バリデーション
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');

  if (nameInput) {
    nameInput.addEventListener('input', () => validateField(nameInput, 'error-name'));
  }
  if (emailInput) {
    emailInput.addEventListener('input', () => validateEmailField(emailInput, 'error-email'));
  }

  // 「確認画面へ進む」ボタン押下
  const bookingForm = document.getElementById('bookingForm');
  if (bookingForm) {
    bookingForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (validateAllFields()) {
        showConfirmScreen();
      }
    });
  }

  // 「戻る」ボタン押下
  const backBtn = document.getElementById('backToInputBtn');
  if (backBtn) backBtn.addEventListener('click', showInputScreen);

  // 「申込完了」ボタン押下
  const finalBtn = document.getElementById('finalSubmitBtn');
  if (finalBtn) finalBtn.addEventListener('click', handleFormSubmit);
}

// 入力画面 ⇄ 確認画面の切り替え
function showConfirmScreen() {
  // 入力値をテーブルに反映
  document.getElementById('confirm-date').innerText = document.getElementById('date').value;
  document.getElementById('confirm-time').innerText = document.getElementById('time').value + '〜';
  document.getElementById('confirm-name').innerText = document.getElementById('name').value;
  document.getElementById('confirm-email').innerText = document.getElementById('email').value;
  document.getElementById('confirm-phone').innerText = document.getElementById('phone').value || '（未入力）';
  document.getElementById('confirm-comment').innerText = document.getElementById('comment').value || '（なし）';

  // 表示の切り替え
  document.getElementById('bookingForm').style.display = 'none';
  document.getElementById('confirmArea').style.display = 'block';
  document.getElementById('confirm-alert').style.display = 'block';

  // ステップナビの強調を移動
  document.getElementById('step-1').classList.remove('active');
  document.getElementById('step-2').classList.add('active');

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showInputScreen() {
  document.getElementById('bookingForm').style.display = 'block';
  document.getElementById('confirmArea').style.display = 'none';
  document.getElementById('confirm-alert').style.display = 'none';

  document.getElementById('step-2').classList.remove('active');
  document.getElementById('step-1').classList.add('active');
}

// テキスト入力の必須チェック
function validateField(input, errorId) {
  const errorBox = document.getElementById(errorId);
  if (!input.value.trim()) {
    input.classList.add('input-error');
    if (errorBox) errorBox.classList.add('show');
    return false;
  } else {
    input.classList.remove('input-error');
    if (errorBox) errorBox.classList.remove('show');
    return true;
  }
}

// メールアドレスの形式チェック
function validateEmailField(input, errorId) {
  const errorBox = document.getElementById(errorId);
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!input.value.trim() || !emailPattern.test(input.value.trim())) {
    input.classList.add('input-error');
    if (errorBox) errorBox.classList.add('show');
    return false;
  } else {
    input.classList.remove('input-error');
    if (errorBox) errorBox.classList.remove('show');
    return true;
  }
}

function validateAllFields() {
  const isNameValid = validateField(document.getElementById('name'), 'error-name');
  const isEmailValid = validateEmailField(document.getElementById('email'), 'error-email');
  return isNameValid && isEmailValid;
}

function sanitize(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .trim();
}

async function handleFormSubmit() {
  const statusDiv = document.getElementById('status');
  const finalSubmitBtn = document.getElementById('finalSubmitBtn');
  const backToInputBtn = document.getElementById('backToInputBtn');

  finalSubmitBtn.disabled = true;
  backToInputBtn.disabled = true;
  statusDiv.style.color = '#333';
  statusDiv.innerText = '予約しています...';

  const payload = {
    title: `${sanitize(document.getElementById('name').value)}様 予約`,
    date: sanitize(document.getElementById('date').value),
    time: sanitize(document.getElementById('time').value),
    name: sanitize(document.getElementById('name').value),
    email: sanitize(document.getElementById('email').value),
    phone: sanitize(document.getElementById('phone').value),
    comment: sanitize(document.getElementById('comment').value)
  };

  try {
    const response = await fetch(GAS_WEB_APP_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'text/plain' },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (result.status === 'success') {
      // 完了ステータスへ移行
      document.getElementById('step-2').classList.remove('active');
      document.getElementById('step-3').classList.add('active');
      document.getElementById('confirmArea').style.display = 'none';
      document.getElementById('confirm-alert').style.display = 'none';

      statusDiv.style.color = 'green';
      statusDiv.style.fontSize = '18px';
      statusDiv.innerText = 'お申し込みが完了しました！ご登録のメールアドレスをご確認ください。';
    } else {
      throw new Error(result.message);
    }
  } catch (err) {
    statusDiv.style.color = 'red';
    statusDiv.innerText = 'エラーが発生しました: ' + err.message;
    finalSubmitBtn.disabled = false;
    backToInputBtn.disabled = false;
  }
}

document.addEventListener('click', (e) => {
  if (!e.target.closest('.day-cell')) {
    document.querySelectorAll('.day-cell.active-cell').forEach(c => c.classList.remove('active-cell'));
  }
});
