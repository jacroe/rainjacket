def rainjacketTemplate(isDay, isPrecip, isStopping):
	if isDay:
		if isPrecip:
			if isStopping:
				return "It's a $tempAdj$ day with highs in the $temp$ and $topPrecipType$ from $startPrecipTime$ to $endPrecipTime$. Be sure to bring a RAIN JACKET. Get it? I need friends..."
			else:
				return "It's a $tempAdj$ day with highs in the $temp$ and $topPrecipType$ starting at $startPrecipTime$ and continuing throughout the day. Be sure to bring a RAIN JACKET. Get it? I need friends..."
		else:
			return "It's a $tempAdj$ day with highs in the $temp$. Something something, sweater weather."
	else:
		if isPrecip:
			if isStopping:
				return "Lows tonight will be in the $temp$. $topPrecipType$ from $startPrecipTime$ to $endPrecipTime$. Sounds like perfect cuddling weather."
			else:
				return "Lows tonight will be in the $temp$. $topPrecipType$ from $startPrecipTime$ and continuing throughout the night. Sounds like great cuddling weather."
		else:
			return "Lows tonight will be in the $temp$. Hot chocolate, anyone?"