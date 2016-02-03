import xlrd;
wb = xlrd.open_workbook('time_tables/sample.xlsx')

# Load XLRD Excel Reader

sheetname = wb.sheet_names() #Read for XCL Sheet names
sh1 = wb.sheet_by_index(0) #Login

# print sh1.nrows
rooms = {}

def readRows():
    for rownum in range(sh1.nrows):
        rowValues = sh1.row_values(rownum)
        # print rowValues
        if(rowValues[1]!=''):
            for i in range(len(rowValues)):
                if((rowValues[i]).replace('\n','')=='LECTURE'):
                    l = i
            if((rowValues[l]).replace('\n','')=='LECTURE'):
        		# print "LECTURE2"
                rownum += 1
                raw_slots = sh1.row_values(rownum)[l].split(';')
                # print "\n"
                slots = []
                for slot in raw_slots:
                    # print slot
                    temp = slot.split(':')
        			# print temp
                    day = temp[0].replace(" ","")
                    # print day
                    time = temp[1]
                    slots.append([day,time])
        		# days = [day.replace(' ','') for day in (sh1.row_values(rownum)[l]).split(',')]
        		# print days
                rownum += 2
            else:
                course = rowValues[0].replace('\n','')
                room = (rowValues[l]).replace('\n','')
                temp2 = [[slot[0],slot[1], course] for slot in slots]
            	if(rooms.has_key(room)):
                        rooms[room] += temp2
            	else:
                        rooms[room] = temp2
            	# print room,rooms[room]
            	# print rowValues[0]
    # return rooms
readRows()
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


def roomStatus(rooms):
    for room in rooms:
	   for day in room:
	       print day," : ",room[day],"\n"

# print roomStatus(sortByDays(rooms))

def main():
    SR = sortByDays(rooms)
    # print SR['LH 418'][1];
    # print SR['LH 418'][4];
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
