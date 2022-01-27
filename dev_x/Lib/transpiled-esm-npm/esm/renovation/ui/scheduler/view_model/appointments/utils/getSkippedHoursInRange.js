var getSkippedHoursInRange = (startDate, endDate, viewDataProvider) => {
  var startTime = startDate.getTime();
  var endTime = endDate.getTime() - 1;
  var hoursInDay = 24;
  var allDayIntervalDuration = hoursInDay * 1000 * 3600;
  var excludedHours = 0;

  for (var time = startTime; time <= endTime; time += allDayIntervalDuration) {
    var checkDate = new Date(time);

    if (viewDataProvider.isSkippedDate(checkDate)) {
      excludedHours += hoursInDay;
    }
  }

  return excludedHours;
};

export default getSkippedHoursInRange;