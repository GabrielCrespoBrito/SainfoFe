let appMath = function()
{
  this.currentValue = 0;

  return {
    
    sum : function()
    {
      arguments.map(function(value){this.currentValue = currentValue + value });
      return this;
    }
  }
}


appMath.sum(1,21,3,4,65,76,2,2).div(5).round(2).sum(424242).get();