from time import strftime, localtime
from forecastio import *
from forecastCrunch import *
from forecastPretty import *
from forecastTemplate import *
import sys

# ------------------------------

apikey, latitude, longitude = "abc1234", 31.327119, -89.290339 # Hattiesburg
#latitude, longitude = 33.1240, -89.0554 # Louisville
#latitude, longitude = 34.113383, -118.404133 # Beverly Hills
# ------------------------------

if len(sys.argv) is 3:
	latitude, longitude = sys.argv[1], sys.argv[2]

if int(strftime("%H", localtime())) <= 12:
	isDay = True
else:
	isDay = False


f = Forecastio(apikey, latitude, longitude)
#print f.url()
if not f.weGood():
	print "Could not get Forecast.io data."
	quit(1)

dictHighsLows = crunchHighsLows(f.getHourlyData())
dictPrecip = crunchChanceOfRain(f.getHourlyData())


if isDay:
	temp = dict(
		temp=prettyTemp(dictHighsLows["hi"]["temp"]),
		adj=prettyTempAdj(dictHighsLows["hi"]["temp"])
	)
else:
	temp = dict(
		temp=prettyTemp(dictHighsLows["lo"]["temp"]),
		adj=prettyTempAdj(dictHighsLows["lo"]["temp"])
	)

dataDict = dict(temp=temp, precipitation=dictPrecip)

#print dataDict
print prettyOutput(dataDict, isDay)
