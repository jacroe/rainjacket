from time import strftime, localtime
from forecastio import *
from forecastCrunch import *
import sys, json

# ------------------------------

apikey, latitude, longitude = "abc1234", 31.327119, -89.290339 # Hattiesburg
#latitude, longitude = 33.1240, -89.0554 # Louisville
#latitude, longitude = 34.113383, -118.404133 # Beverly Hills
# ------------------------------

"""If we are provided with coordinates, use those."""
if len(sys.argv) is 3:
	latitude, longitude = sys.argv[1], sys.argv[2]


"""Do the lookup and make sure it was a good request."""
f = Forecastio(apikey, latitude, longitude)
#print f.url()
if not f.weGood():
	print "Could not get Forecast.io data."
	quit(1)

"""Crunch the highs and lows, precipitation."""
dictHighsLows = crunchHighsLows(f.getHourlyData())
dictPrecip = crunchChanceOfRain(f.getHourlyData())
dictLookingAhead = crunchLookingAhead(f.getHourlyData())

"""Form a new dict with the aforementioned crunched data."""
dataDict = dict(temp=dictHighsLows, precipitation=dictPrecip, lookingAhead=dictLookingAhead)

"""Convert to JSON and print."""
print json.dumps(dataDict)