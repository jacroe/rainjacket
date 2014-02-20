from time import strftime, gmtime, localtime
from datetime import datetime, date, time, timedelta

def crunchHighsLows(hourlyData):
	tomorrow = datetime.combine(date.today(), time.min) + timedelta(days=1)
	lo, hi = None, None
	for i in range(0, len(hourlyData)):
		curHour = hourlyData[i]
		if datetime.fromtimestamp(int(curHour["time"])) >= tomorrow:
			break
		if curHour["temperature"] > hi:
			hi = curHour["temperature"]
			hiTime = curHour["time"]
		if curHour["temperature"] < lo or lo is None:
			lo = curHour["temperature"]
			loTime = curHour["time"]
	return dict(
		hi=dict(temp=int(round(hi)), timestamp=hiTime),
		lo=dict(temp=int(round(lo)), timestamp=loTime) )

def crunchChanceOfRain(hourlyData):
	tomorrow = datetime.combine(date.today(), time.min) + timedelta(days=1)
	topPrecipChance = 0
	startTime = False
	for i in range(0, len(hourlyData)):
		curHour = hourlyData[i]

		# If we're looking at tomorrow, break
		if datetime.fromtimestamp(int(curHour["time"])) >= tomorrow:
			break

		if curHour["precipProbability"] > topPrecipChance:
			topPrecipChance = curHour["precipProbability"]
			topPrecipTime = curHour["time"]
			topPrecipType = curHour["precipType"]
			topPrecipIntensity = curHour["precipIntensity"]

		if curHour["precipProbability"] > .2 and startTime is False:
			startTime = True
			startPrecipChance = curHour["precipProbability"]
			startPrecipTime = curHour["time"]
			startPrecipType = curHour["precipType"]
			startPrecipIntensity = curHour["precipIntensity"]

	endTime = 0
	if startTime is not False:
		for i in range(0, len(hourlyData)):
			curHour = hourlyData[i]

			if datetime.fromtimestamp(int(curHour["time"])) >= tomorrow:
				break

			if curHour["time"] <= topPrecipTime:
				continue
			if curHour["precipProbability"] < .2:
				endTime = curHour["time"]
				break

	
	if topPrecipChance < .2:
		return None
	else:
		return dict(
			top=dict(chance=topPrecipChance, time=topPrecipTime, type=topPrecipType, intensity=topPrecipIntensity),
			start=dict(chance=startPrecipChance, time=startPrecipTime, type=startPrecipType, intensity=startPrecipIntensity),
			endTime=endTime
			)
