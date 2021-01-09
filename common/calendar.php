<HTML>
<HEAD>
<TITLE>달력</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="JASKO">
<META NAME="Keywords" CONTENT="JavaScript, 자바스크립트">

<!-- 1. 메모장 등으로 아래의 내용을 HTML 문서의 HEAD 부분에 붙여 넣으세요 -->

<style type="text/css">
a:link { text-decoration: none;}
a:visited { text-decoration: none;}
TD { text-align: center; vertical-align: middle;}
.CalHead { font:bold 8pt Arial; color: white;}
.CalCell { font:8pt Arial; cursor: hand;}
.HeadBtn { vertical-align:middle; height:22; width:18; font:10pt Fixedsys;}
.HeadBox { vertical-align:middle; font:10pt;}
.Today { font:bold 10pt Arial; color:white;}
</style>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>

<!-- 2. 메모장 등으로 아래의 방법으로 HTML 문서의 BODY 부분에 붙여 넣으세요 -->

<script>
<!--
/******** 환경설정 부분 *******************************************************/

var gdCurDate = new Date();
var giYear = gdCurDate.getFullYear();
var giMonth = gdCurDate.getMonth()+1;
var giDay = gdCurDate.getDate();
var giStartYear = giYear;
var giEndYear = giYear+1;
var giCellWidth = 16;
var gMonths = new Array("1","2","3","4","5","6","7","8","9","10","11","12");
var gcOtherDay = "gray";
var gcToggle = "#666666";
var gcBG = "#ffffff";
var gcTodayBG = "orange";
var gcFrame = "#aaaaaa";
var gcHead = "white";
var gcWeekend = "red";
var gcWorkday = "black";
var gcCalBG = "#ffffff";
//-->

var gcTemp = gcBG;
var tbMonSelect, tbYearSelect;
var gCellSet = new Array;

function fSetDate(iYear, iMonth, iDay){
	var iYear = iYear - giStartYear;
	var iMonth = iMonth - 1;
	var iDay = iDay - 1;
	opener.document.form<?=$_GET['mode']?>.<?=$_GET['type']?>year.options[iYear].selected = true;
	opener.document.form<?=$_GET['mode']?>.<?=$_GET['type']?>month.options[iMonth].selected = true;
	opener.document.form<?=$_GET['mode']?>.<?=$_GET['type']?>day.options[iDay].selected = true;
	window.close();
}

function fSetSelected(aCell){
  var iOffset = 0;
  var iYear = parseInt(tbSelYear.value);
  var iMonth = parseInt(tbSelMonth.value);

  aCell.bgColor = gcBG;
  with (aCell.firstChild){
  	var iDate = parseInt(innerHTML);
  	if (style.color==gcOtherDay)
		iOffset = (id<10)?-1:1;
	iMonth += iOffset;
	if (iMonth<1) {
		iYear--;
		iMonth = 12;
	}else if (iMonth>12){
		iYear++;
		iMonth = 1;
	}
  }

  fSetDate(iYear, iMonth, iDate);
}

function fBuildCal(iYear, iMonth) {
  var aMonth=new Array();
  for(i=1;i<7;i++)
  	aMonth[i]=new Array(i);

  var dCalDate=new Date(iYear, iMonth-1, 1);
  var iDayOfFirst=dCalDate.getDay();
  var iDaysInMonth=new Date(iYear, iMonth, 0).getDate();
  var iOffsetLast=new Date(iYear, iMonth-1, 0).getDate()-iDayOfFirst+1;
  var iDate = 1;
  var iNext = 1;

  for (d = 0; d < 7; d++)
	aMonth[1][d] = (d<iDayOfFirst)?-(iOffsetLast+d):iDate++;
  for (w = 2; w < 7; w++)
  	for (d = 0; d < 7; d++)
		aMonth[w][d] = (iDate<=iDaysInMonth)?iDate++:-(iNext++);
  return aMonth;
}

function fDrawCal(iCellWidth) {
  var WeekDay = new Array("일","월","화","수","목","금","토");
  var styleTD = " width='"+iCellWidth+"' ";

  with (document) {
	write("<tr>");
	for(i=0; i<7; i++)
		write("<td class='CalHead' "+styleTD+">" + WeekDay[i] + "</td>");
	write("</tr>");

  	for (w = 1; w < 7; w++) {
		write("<tr>");
		for (d = 0; d < 7; d++) {
			write("<td class='CalCell' "+styleTD+" onMouseOver='gcTemp=this.bgColor;this.bgColor=gcToggle;this.bgColor=gcToggle' onMouseOut='this.bgColor=gcTemp;this.bgColor=gcTemp' onclick='fSetSelected(this)'>");
			write("<A href='#null' onfocus='this.blur();'>00</A></td>")
		}
		write("</tr>");
	}
  }
}

function fUpdateCal(iYear, iMonth) {
  myMonth = fBuildCal(iYear, iMonth);
  var i = 0;
  var iDate = 0;
  for (w = 0; w < 6; w++)
	for (d = 0; d < 7; d++)
		with (gCellSet[(7*w)+d]) {
			id = i++;
			if (myMonth[w+1][d]<0) {
				style.color = gcOtherDay;
				innerHTML = -myMonth[w+1][d];
				iDate = 0;
			}else{
				style.color = ((d==0)||(d==6))?gcWeekend:gcWorkday;
				innerHTML = myMonth[w+1][d];
				iDate++;
			}
			parentNode.bgColor = ((iYear==giYear)&&(iMonth==giMonth)&&(iDate==giDay))?gcTodayBG:gcBG;
			parentNode.bgColor = parentNode.bgColor;
		}
}

function fSetYearMon(iYear, iMon){
  tbSelMonth.options[iMon-1].selected = true;
  if (iYear>giEndYear) iYear=giEndYear;
  if (iYear<giStartYear) iYear=giStartYear;
  tbSelYear.options[iYear-giStartYear].selected = true;
  fUpdateCal(iYear, iMon);
}

function fPrevMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (--iMon<1) {
	  iMon = 12;
	  iYear--;
  }

  fSetYearMon(iYear, iMon);
}

function fNextMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (++iMon>12) {
	  iMon = 1;
	  iYear++;
  }

  fSetYearMon(iYear, iMon);
}

with (document) {
  write("<table id='popTable' bgcolor='"+gcCalBG+"' cellspacing='0' cellpadding='3' border='0'><TR>");
  write("<td align='center'><input type='button' value='<' class='HeadBtn' onClick='fPrevMonth()' style='border:0px ; background-color:ffffff ; cursor:pointer' onfocus='blur()'>");
  write("&nbsp;<select id='tbYearSelect' class='HeadBox' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' style='font-size:11px'>");
  for(i=giStartYear;i<=giEndYear;i++)
	write("<OPTION value='"+i+"'>"+i+"</OPTION>");
  write("</select><select id='tbMonSelect' class='HeadBox' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' style='font-size:11px'>");
  for (i=0; i<12; i++)
	write("<option value='"+(i+1)+"'>"+gMonths[i]+"</option>");
  write("</select>&nbsp;<input type='button' value='>' class='HeadBtn' onclick='fNextMonth()' style='border:0px ; background-color:ffffff ; cursor:pointer' onfocus='blur()'>");
  write("</td></TR><TR><td align='center'>");
  write("<DIV style='background-color:"+gcFrame+";width:"+((giCellWidth+6)*7+2)+"px;'><table border='0' cellpadding='3' cellspacing='1' >");
  tbSelMonth = getElementById("tbMonSelect");
  tbSelYear = getElementById("tbYearSelect");
  fDrawCal(giCellWidth);
  gCellSet = getElementsByTagName("A");
  fSetYearMon(giYear, giMonth);
  write("</table></DIV></td></TR><TR><TD align='center'>");
  write("<A href='#null' class='Today' onclick='fSetDate(giYear,giMonth,giDay); this.blur();'><font color=#666666>Today "+giYear+"년 "+gMonths[giMonth-1]+"월 "+giDay+"일</font></A>");
  write("</TD></TR></TD></TR><TR></TR></TABLE>");

  // parent.document.getElementById(self.name).width = getElementById("popTable").offsetWidth;
  // parent.document.getElementById(self.name).height = getElementById("popTable").offsetHeight;
}
// -->
</script>

</body>
</html>
