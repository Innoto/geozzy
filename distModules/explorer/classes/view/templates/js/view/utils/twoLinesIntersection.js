function twoLinesIntersection() {
  var that = this;
  // line-segments-intersect.js
  // intersection point https://en.wikipedia.org/wiki/Line%E2%80%93line_intersection
  // line 1: x1,y1,x2,y2
  // line 2: x3,y3,x4,y4
  // for comparing the float number, that.fixing the number to int to required
  // precision
  that.linesIntersect = function(seg1, seg2, precision) {
    var x1 = seg1[0][0],
      y1 = seg1[0][1],
      x2 = seg1[1][0],
      y2 = seg1[1][1],
      x3 = seg2[0][0],
      y3 = seg2[0][1],
      x4 = seg2[1][0],
      y4 = seg2[1][1],
      intPt,x,y,result = false,
      p = precision || 6,
      denominator = (x1 - x2)*(y3 - y4) - (y1 -y2)*(x3 - x4);
    if (denominator == 0) {
      // check both segments are Coincident, we already know
      // that these two are parallel
      if (that.fix((y3 - y1)*(x2 - x1),p) == that.fix((y2 -y1)*(x3 - x1),p)) {
        // second segment any end point lies on first segment
        result = that.intPtOnSegment(x3,y3,x1,y1,x2,y2,p) ||
          that.intPtOnSegment(x4,y4,x1,y1,x2,y2,p);
      }
    } else {
      x = ((x1*y2 - y1*x2)*(x3 - x4) - (x1 - x2)*(x3*y4 - y3*x4))/denominator;
      y = ((x1*y2 - y1*x2)*(y3 - y4) - (y1 - y2)*(x3*y4 - y3*x4))/denominator;
      // check int point (x,y) lies on both segment
      result = that.intPtOnSegment(x,y,x1,y1,x2,y2,p)
        && that.intPtOnSegment(x,y,x3,y3,x4,y4,p);
    }
    return result;
  };

  that.intPtOnSegment = function(x,y,x1,y1,x2,y2,p) {
    return that.fix(Math.min(x1,x2),p) <= that.fix(x,p) && that.fix(x,p) <= that.fix(Math.max(x1,x2),p)
      && that.fix(Math.min(y1,y2),p) <= that.fix(y,p) && that.fix(y,p) <= that.fix(Math.max(y1,y2),p);
  };

  // that.fix to the precision
  that.fix = function(n,p) {
    return parseInt(n * Math.pow(10,p));
  };

}
