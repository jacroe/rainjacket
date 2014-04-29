from time import strftime, gmtime, localtime
from datetime import datetime, date, time, timedelta

def crunchHighsLows(hourlyData):
	"""Return a dict of the highs and lows of the day along with the unix epoch of the hour they occur."""
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
	"""
	Return a dict that describes the precipitation when it starts, when it ends, and when it reaches its peak.

	For when it starts and peaks, it gives the probability, the unix epoch, the type, and the intensity.
	For when it ends, it retuns only the unix epoch.
	"""
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

def crunchWindSpeed(hourlyData):
	tomorrow = datetime.combine(date.today(), time.min) + timedelta(days=1)
	topWindSpeed = 0
	for i in range(0, len(hourlyData)):
		curHour = hourlyData[i]

		# If we're looking at tomorrow, break
		if datetime.fromtimestamp(int(curHour["time"])) >= tomorrow:
			break

		if curHour["windSpeed"] > topWindSpeed:
			topWindSpeed = curHour["windSpeed"]

	topWindSpeed = int(round(topWindSpeed))
	return topWindSpeed

def crunchLookingAhead(hourlyData):
	icons = {"clear-day" : "sunny", "clear-night" : "sunny", "rain" : "rain", "snow" : "snow", "sleet" : "sleet", "wind" : "windy", "fog" : "fog", "cloudy" : "cloudy", "partly-cloudy-day" : "mostlysunny", "partly-cloudy-night" : "mostlysunny"}

	data = list()
	data.append( dict(condition=hourlyData[0]["summary"], image=icons[hourlyData[0]["icon"]], temp=int(round(hourlyData[0]["temperature"])), time=hourlyData[0]["time"]) )
	data.append( dict(condition=hourlyData[4]["summary"], image=icons[hourlyData[4]["icon"]], temp=int(round(hourlyData[4]["temperature"])), time=hourlyData[4]["time"]) )
	data.append( dict(condition=hourlyData[8]["summary"], image=icons[hourlyData[8]["icon"]], temp=int(round(hourlyData[8]["temperature"])), time=hourlyData[8]["time"]) )

	return data

def crunchAlerts(alertData):
	if alertData is not False:
		data = list()
		for i in range(0, len(alertData)):
			data.append( dict(expires=alertData[i]["expires"], title=alertData[i]["title"], uri=alertData[i]["uri"], time=alertData[i]["time"]))
		return data
	else:
		return None