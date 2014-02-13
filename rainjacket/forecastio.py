import json, requests

class Forecastio():

	def __init__(self, apikey, latitude, longitude):
		self.__url = "https://api.forecast.io/forecast/" + apikey + "/" + str(latitude) + "," + str(longitude)
		r = requests.get(self.__url)

		if r.status_code is 200:
			self.__forecastioData = r.json()
			self.__weGood = True
		else:
			self.__weGood = False

	def url(self):
		return self.__url

	def weGood(self):
		return self.__weGood

	def getMinutelyData(self):
		return self.__forecastioData["minutely"]["data"]
	def getMinutelySum(self):
		return self.__forecastioData["minutely"]["summary"]

	def getDailyData(self):
		return self.__forecastioData["daily"]["data"]
	def getDailySum(self):
		return self.__forecastioData["daily"]["summary"]

	def getHourlyData(self):
		return self.__forecastioData["hourly"]["data"]
	def getHourlySum(self):
		return self.__forecastioData["hourly"]["summary"]

	def getTodayData(self):
		return self.getDailyData()[0]

	def getCurrent(self):
		return self.__forecastioData["currently"]

	def getAlerts(self):
		if "alerts" in self.__forecastioData:
			return self.__forecastioData["alerts"]
		else:
			return False