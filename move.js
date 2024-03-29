function loginClick(){
    fastId = document.getElementById("attention");
    fastId.setAttribute(name,"loginIn")
    alert(fastId);
}

//カレンダー作成
const weeks = ["日","月","火","水","木","金","土"]
const date = new Date()
let year = date.getFullYear()
let month = date.getMonth()+1
const config = {
    show:3,
}
function showCalendar(year, month) {
    for (let i = 0; i < config.show; i++) {
        const calendarHtml = createCalendar(year, month)
        const sec = document.createElement('section')
        sec.innerHTML = calendarHtml
        document.querySelector('#calendar').appendChild(sec)

        month++
        if (month > 12) {
            year++
            month = 1
        }
    }
}

function createCalendar(year, month) {
    const startDate = new Date(year, month - 1, 1) // 月の最初の日を取得
    const endDate = new Date(year, month,  0) // 月の最後の日を取得
    const endDayCount = endDate.getDate() // 月の末日
    const lastMonthEndDate = new Date(year, month - 2, 0) // 前月の最後の日の情報
    const lastMonthendDayCount = lastMonthEndDate.getDate() // 前月の末日
    const startDay = startDate.getDay() // 月の最初の日の曜日を取得
    let dayCount = 1 // 日にちのカウント
    let calendarHtml = '' // HTMLを組み立てる変数

    calendarHtml += '<h1>' + year  + '/' + month + '</h1>'
    calendarHtml += '<table class = "table">'

    // 曜日の行を作成
    for (let i = 0; i < weeks.length; i++) {
        calendarHtml += '<td>' + weeks[i] + '</td>'
    }

    for (let w = 0; w < 6; w++) {
        calendarHtml += '<tr>'

        for (let d = 0; d < 7; d++) {
            if (w == 0 && d < startDay) {
                 // 1行目で1日の曜日の前
                let num = lastMonthendDayCount - startDay + d + 1
                calendarHtml += '<td class="is-disabled">' + num + '</td>'
            } else if (dayCount > endDayCount) {
                // 末尾の日数を超えた
                let num = dayCount - endDayCount
                calendarHtml += '<td class="is-disabled">' + num + '</td>'
                dayCount++
            } else {
                month=("0"+month).slice(-2)
                dayCount=("0"+dayCount).slice(-2)
                calendarHtml += `<td class="calendar_td" data-date="${year}/${month}/${dayCount}">${dayCount}</td>`
                dayCount++
            }
        }
        calendarHtml += '</tr>'
    }
    calendarHtml += '</table>'

    return calendarHtml
}
function moveCalendar(e) {
    document.querySelector('#calendar').innerHTML = ''

    if (e.target.id === 'prev') {
        month--

        if (month < 1) {
            year--
            month = 12
        }
    }

    if (e.target.id === 'next') {
        month++

        if (month > 12) {
            year++
            month = 1
        }
    }

    showCalendar(year, month)
}

//前に戻る
document.querySelector('#prev').addEventListener('click', moveCalendar)
//次の月
document.querySelector('#next').addEventListener('click', moveCalendar)

//カレンダーの日付が押されたら
document.addEventListener("click", function(e) {
    if(e.target.classList.contains("calendar_td")) {
        let element = document.getElementById('date');
        element.value = e.target.dataset.date;
        document.getElementById("test").click();

        console.log(element.value)


    }
})

showCalendar(year, month)

function edit(e){
    var e = e || window.event;
    var elem = e.target || e.srcElement;
    var elemId = elem.className;
    console.log("kita");
    console.log(elemId);
    var element = elemId;
    document.getElementById( "scheduleKey" ).value = element ;
    console.log(element);
}
