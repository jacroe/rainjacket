from time import strftime, gmtime, localtime
from forecastTemplate import *

def prettyOutput(dataDict, isDay):
	replaceDict = {
		'$tempAdj$':dataDict["temp"]["adj"],
		'$temp$':dataDict["temp"]["temp"]
	}

	if dataDict["precipitation"] is not None:
		replaceDict.update(
			{
				'$topPrecipType$':prettyPrecipType(dataDict["precipitation"]["top"]["type"],dataDict["precipitation"]["top"]["intensity"]),
				'$topPrecipTime$':prettyTime(localtime(dataDict["precipitation"]["top"]["time"])),
				'$topPrecipchance$':prettyPrecipChance(dataDict["precipitation"]["top"]["chance"]),

				'$startPrecipType$':prettyPrecipType(dataDict["precipitation"]["start"]["type"],dataDict["precipitation"]["start"]["intensity"]),
				'$startPrecipTime$':prettyTime(localtime(dataDict["precipitation"]["start"]["time"])),
				'$startPrecipchance$':prettyPrecipChance(dataDict["precipitation"]["start"]["chance"]),

				'$endPrecipTime$':prettyTime(localtime(dataDict["precipitation"]["endTime"]))			
			}
		)
		template = rainjacketTemplate(isDay, bool(dataDict["precipitation"]["start"]["chance"]), bool(dataDict["precipitation"]["endTime"]))
	else:
		template = rainjacketTemplate(isDay, False, False)

	for k,v in replaceDict.items():
		template = template.replace(k,v)

	return template

def prettyTemp(temp):
	strTemp = str(temp)
	if int(strTemp[-1]) < 4:
		strTempLoMidHi = "low "
	elif int(strTemp[-1]) < 7:
		strTempLoMidHi = ""
	else:
		strTempLoMidHi = "high "

	return strTempLoMidHi + strTemp[:-1] + "0s"

def prettyTime(time):
	hour, minute, ampm = strftime("%I", time), strftime("%M", time), strftime("%p", time)

	hour = str(int(hour))
	if ampm == "AM":
		ampm = ""
	else:
		ampm = "p"

	if minute == "00":
		minute = ""
	else:
		minute = ":" + minute

	return hour + minute + ampm
def prettyTempAdj(temp):
	if temp >= 90:
		adj = "bloody hot"
	elif temp >= 80:
		adj = "warm"
	elif temp >= 70:
		adj = "nice and cozy"
	elif temp >= 60:
		adj = "cool"
	elif temp >= 50:
		adj = "chilly"
	elif temp >= 40:
		adj = "cold"
	elif temp >= 30:
		adj = "freezing"
	else:
		adj = "freakin' cold"

	return adj

def prettyPrecipType(precipType, intensity):
	types = dict(
		rain=(["drizzle", "light rain", "rain", "heavy rain"]),
		snow=(["snow flurries", "snow", "snow", "heavy snow"]),
		sleet=(["sleet", "sleet", "sleet", "sleet"]),
		hail=(["hail", "hail", "hail", "heavy hail"])
	)
	if intensity >= 0.4:
		return types[precipType][3]
	elif intensity >= 0.1:
		return types[precipType][2]
	elif intensity >= 0.017:
		return types[precipType][1]
	else:
		return types[precipType][0]

def prettyPrecipChance(chance):
	# https://en.wikipedia.org/wiki/Probability_of_precipitation
	if chance > .8:
		return ""
	elif chance > .4:
		return "a chance of "
	else:
		return "a slight chance of "

