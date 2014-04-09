import json, requests, MySQLdb
from datetime import datetime, timedelta

db = MySQLdb.connect(host="localhost", user="root", passwd="password", db="rainjacket")

class Forecastio():
	"""Get the latest data from Forecastio and create functions for handling it."""

	def __init__(self, apikey, latitude, longitude):
		latitude, longitude = str(latitude), str(longitude)
		self.__url = "https://api.forecast.io/forecast/" + apikey + "/" + latitude + "," + longitude

		#db = MySQLdb.connect(host="localhost", user="root", passwd="#3n.7B", db="rainjacket")
		cur = db.cursor()

		cur.execute("SELECT * FROM forecastio WHERE `location` = %s LIMIT 1", latitude+","+longitude)

		row = cur.fetchone()
		if row is not None and row[2] > datetime.now():
			self.__forecastioData = json.loads(unicode(row[1], "ISO-8859-1"))
			self.__weGood = True
		else:
			"""Do the actual lookup and make sure we have a good request."""
			r = requests.get(self.__url)

			if r.status_code is 200:
				self.__forecastioData = r.json()
				self.__weGood = True
			else:
				self.__weGood = False


			"""Cache results until the next hour"""
			if row is not None:
				cur.execute("""UPDATE `forecastio` SET `data`=%s, `expires`=%s WHERE `location`=%s""", (r.text, (datetime.today() + timedelta(hours=1)).strftime("%Y-%m-%d %H:00:00"), latitude+","+longitude))
			else:
				cur.execute("""INSERT INTO `forecastio` (`data`, `expires`, `location`) VALUES (%s, %s, %s)""", (r.text, (datetime.today() + timedelta(hours=1)).strftime("%Y-%m-%d %H:00:00"), latitude+","+longitude))
			cur.connection.commit()

	def url(self):
		"""Return the url we used."""
		return self.__url

	def weGood(self):
		"""Return if it was a good lookup or not."""
		return self.__weGood

	def getMinutelyData(self):
		"""Get the minutely data array."""
		return self.__forecastioData["minutely"]["data"]
	def getMinutelySum(self):
		"""Get the summary of the minutely report."""
		return self.__forecastioData["minutely"]["summary"]

	def getDailyData(self):
		"""Get the daily data array."""
		return self.__forecastioData["daily"]["data"]
	def getDailySum(self):
		"""Get the daily data summary."""
		return self.__forecastioData["daily"]["summary"]

	def getHourlyData(self):
		"""Get the hourly data array."""
		return self.__forecastioData["hourly"]["data"]
	def getHourlySum(self):
		"""Get the hourly data summary."""
		return self.__forecastioData["hourly"]["summary"]

	def getTodayData(self):
		"""From the hourly data, get today's (entry 0)."""
		return self.getDailyData()[0]

	def getCurrent(self):
		"""Return the currently string provided by Forecastio."""
		return self.__forecastioData["currently"]

	def getAlerts(self):
		"""If there are any alerts, return that block. Else, return false."""
		if "alerts" in self.__forecastioData:
			return self.__forecastioData["alerts"]
		else:
			return False