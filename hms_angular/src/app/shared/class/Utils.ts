import * as moment from 'moment';
import Moment from 'moment-timezone';
export function formatTime(datetime)
{
    
    if(datetime != "")
    {
        
        //var convertedDateString = datetime.toLocaleString();
        //convertedDateString = convertedDateString.replace('at ', '');
        var convertedDate = moment.utc(datetime).toDate(); //, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y hh:mm a');
        // console.log(convertedDate);
        var retval = moment(convertedDate).format('hh:mm a');
        //var convertedDate = new Date(convertedDateString);
        return  retval;
    }
    return  datetime;
}

export function formatDateTime(datetime)
{
    
    if(datetime != "")
    {
        var convertedDate = moment.utc(datetime).toDate(); 
        var retval = moment(convertedDate).format('DD-MM-Y hh:mm a');
        return  retval;
    }
    return  datetime;
}

export function defaultDateTime()
{
    var date = localStorage.getItem('default_date');
    if(date != "" && date != null  && date != '0000-00-00')
    {
        var dt = new Date(date)
        if (Object.prototype.toString.call(dt) === "[object Date]") {
            // it is a date
            if (isNaN(dt.getTime())) {  // d.valueOf() could also work
                return  new Date();
            } 
            else 
            {
                var dtFormat = moment(dt).format('Y-MM-DD');
                var curDate = new Date();
                var time = moment(curDate).format('THH:mm:ss');
                var text = dtFormat+time
                // console.log(date);
                // console.log(text);
                // console.log(new Date(text));
                return new Date(text);
            }
        } 
        else 
        {
            return  new Date();
        }
    }
    return  new Date();
}
export function getTimeZone()
{
    return  Moment.tz.guess();
}
export function formatDate(datetime)
{
    
    if(datetime != "")
    {
        var convertedDate = moment.utc(datetime).toDate();
        var retval = moment(convertedDate).format('DD-MM-Y');
        return  retval;
    }
    return  datetime;
}
export function formatByDate(datetime)
{
    
    if(datetime != "")
    {
        var convertedDate = moment.utc(datetime).toDate();
        var retval = moment(convertedDate).format('DD/MM/Y');
        return  retval;
    }
    return  datetime;
}