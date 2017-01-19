/**
  We modified the riginal code from:
  @gagan-bansal https://github.com/geosquare/line-segments-intersect, Licensed with MIT 2015
**/


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

  that.getIntersectionPoint = function(line1, line2) {
      // if the lines intersect, the result contains the x and y of the intersection (treating the lines as infinite) and booleans for whether line segment 1 or line segment 2 contain the point
      var denominator, a, b, numerator1, numerator2, result = {
          x: null,
          y: null,
          onLine1: false,
          onLine2: false
      };
      denominator = ((line2[1][1] - line2[0][1]) * (line1[1][0] - line1[0][0])) - ((line2[1][0] - line2[0][0]) * (line1[1][1] - line1[0][1]));
      if (denominator == 0) {
          return result;
      }
      a = line1[0][1] - line2[0][1];
      b = line1[0][0] - line2[0][0];
      numerator1 = ((line2[1][0] - line2[0][0]) * a) - ((line2[1][1] - line2[0][1]) * b);
      numerator2 = ((line1[1][0] - line1[0][0]) * a) - ((line1[1][1] - line1[0][1]) * b);
      a = numerator1 / denominator;
      b = numerator2 / denominator;

      // if we cast these lines infinitely in both directions, they intersect here:
      result.x = line1[0][0] + (a * (line1[1][0] - line1[0][0]));
      result.y = line1[0][1] + (a * (line1[1][1] - line1[0][1]));
  /*
          // it is worth noting that this should be the same as:
          x = line2[0][0] + (b * (line2[1][0] - line2[0][0]));
          y = line2[0][0] + (b * (line2[1][1] - line2[0][1]));
          */
      // if line1 is a segment and line2 is infinite, they intersect if:
      if (a > 0 && a < 1) {
          result.onLine1 = true;
      }
      // if line2 is a segment and line1 is infinite, they intersect if:
      if (b > 0 && b < 1) {
          result.onLine2 = true;
      }
      // if line1 and line2 are segments, they intersect if both of the above are true
      return result;
  };

}
