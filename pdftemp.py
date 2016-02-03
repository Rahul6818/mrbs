import xlrd;
wb = xlrd.open_workbook('time_tables/sample.xlsx')

# Load XLRD Excel Reader

sheetname = wb.sheet_names() #Read for XCL Sheet names
sh1 = wb.sheet_by_index(0) #Login

def readRows():
	rooms = {}
	for rownum in range(sh1.nrows):
		rowValues = sh1.row_values(rownum)
		if(rowValues[1]!=''):
			slots = []
			for i in range(len(rowValues)):
				l = 0
				if((rowValues[i]).replace('\n','')=='LECTURE'):
					l = i
			if((rowValues[l]).replace('\n','')=='LECTURE'):
				rownum += 1
				raw_slots = sh1.row_values(rownum)[l].split(';')
				slots = []
				for slot in raw_slots:
					temp = slot.split(':')
					day = temp[0].replace(" ","")
					time = temp[1]
					slots.append([day,time])
				rownum += 2
			else:
				course = rowValues[0].replace('\n','')
				room = (rowValues[l]).replace('\n','')
				temp2 = [[slot[0],slot[1], course] for slot in slots]
				if(rooms.has_key(room)):
					rooms[room] += temp2
				else:
					rooms[room] = temp2
	return rooms
rooms = readRows()