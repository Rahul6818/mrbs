import xlrd;
wb = xlrd.open_workbook('time_tables/sample.xlsx')

# Load XLRD Excel Reader

sheetname = wb.sheet_names() #Read for XCL Sheet names
sh1 = wb.sheet_by_index(0) #Login

# print sh1.nrows
def readRows(sheet):
	rooms = {}
	for rownum in range(sheet.nrows):
		rowValues = sheet.row_values(rownum)
		if(rowValues[1]!=''):
			# slots = []
			for i in range(len(rowValues)):
				# l = 0
				if((rowValues[i]).replace('\n','')=='LECTURE'):
					l = i
			if((rowValues[l]).replace('\n','')=='LECTURE'):
				rownum += 1
				raw_slots = sheet.row_values(rownum)[l].split(';')
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
def sortByDays(Rooms):
	sortedRooms = {}
	for room in Rooms:
		values = Rooms[room]
		tt = {1:[],2:[],3:[],4:[],5:[],6:[],7:[]}
		for slot in values:
			day = slot[0]
			if('M' == day):
				tt[1].append([str(slot[1]),str(slot[2])])
			elif('Tu'== day):
				tt[2].append([str(slot[1]),str(slot[2])])
			elif('W' == day):
				tt[3].append([str(slot[1]),str(slot[2])])
			elif('Th' == day):
				tt[4].append([str(slot[1]),str(slot[2])])
			elif('F' == day):
				tt[5].append([str(slot[1]),str(slot[2])])
		sortedRooms[room] = tt
	return sortedRooms

# sortByDays(rooms)


# def holidays(sheet):
# 		for rownum in range(1,sheet.nrows):
# 			rowValues = sheet.row_values(rownum)
# 			print rowValues[1]
# wb = xlrd.open_workbook('time_tables/holidays.xls')
# sh_holi = wb.sheet_by_index(0) #Login
# holidays(sh_holi)

def roomStatus(rooms):
    for room in rooms:
	   for day in room:
	       print day," : ",room[day],"\n"

# print roomStatus(sortByDays(rooms))
rooms = readRows(sh1)
def main():
    SR = sortByDays(rooms)
    for room in SR:
        print room.encode('ascii', 'ignore').decode('ascii')
        print SR[room][1]
        print SR[room][2]
        print SR[room][3]
        print SR[room][4]
        print SR[room][5]
        print SR[room][6]
        print SR[room][7]

if __name__ == '__main__':
	print main()
