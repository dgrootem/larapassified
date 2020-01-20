import { parse, format } from "date-fns";
export function isDate(s){
    var patt = new RegExp('^([0-2][0-9]|(3)[0-1])(-)(((0)[0-9])|((1)[0-2]))(-)[1-2][0-9][0-9][0-9]$');
    var result = patt.test(s);
    return result;
}
export function parseDate(val) {
    if (val && val.length >= 10) {
        return parseTheDateYall(val);
    } else return null;
  }
  export function formatDate(date) {
    if (date && date.length >= 10) {
      let d = this.parseDate(date);
      let f = format(d, "dd-MM-yyyy");
      return f;
    } else return null;
  }
  export function formatDateFromDB(datestring) {
    if (datestring && datestring.length >= 10) {
      let d = datestring.substring(0, 10);
      //console.log(d);
      return format(parse(d, "yyyy-MM-dd", new Date()), "dd-MM-yyyy");
    } else return null;
  }
  export function formatDateToDB(date){
      return format(date,'yyyy-MM-dd hh:mm:ss');
  }
  export function parseDateFromDB(datestring){
    if (datestring && datestring.length >= 10) {
      let d = datestring.substring(0, 10);
      return parse(d, "yyyy-MM-dd", new Date());
    }
    else return null;
  }

//thanks to https://github.com/date-fns/date-fns/issues/489 , solution by @maggiepint
function parseTheDateYall (date) {
        const [ day, month, year ] = date.substr(0, 10).split('-');
        return new Date(
                year,
                (month - 1),
                day,
        );
}