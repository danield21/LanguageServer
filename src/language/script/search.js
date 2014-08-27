function Search(list, matcthing, matchedFunction, noMatchFunction) {
	this.list = list;
	this.matching = matcthing;
	this.matchedFunction = matchedFunction;
	this.noMatchFunction = noMatchFunction;
	
	this.search = function() {
		for(var i = 0; i < this.list.length; ++i) {
			if(this.matching.matches(this.list[i])) {
				matchedFunction(this.list[i]);
			} else {
				noMatchFunction(this.list[i]);
			}
		}
	}
}

function Matching() {
	this.matches = function(item) {
		return true;
	}
	
	this.inArray = function(items, list, matchingFunction) {
		console.log(items);
		console.log(list);
		if(typeof matchingFunction === 'undefined') {
			matchingFunction = function (l, r) {return l == r;}
		}
		if(Array.isArray(list)) {
			if(Array.isArray(items)) {
				console.log('item is array');
				for(var i = 0; i < list.length; ++i) {
					var found = false;
					for(var j = 0; j < items.length; ++j) {
						if(matchingFunction(items[i], list[i])) {
							found = true;
						}
					}
					if(!found) {
						console.log("item " + items + " is not found");
						return false;
					}
				}
				console.log("item " + items + " is found");
				return true;
			} else {
				console.log('item is not array');
				for(var i = 0; i < list.length; ++i) {
					if(matchingFunction(items, list[i])) {
						return true;
					}
				}
				return false;
			}
		} else {
			console.log('list is not array');
			return false;
		}
	}
	
	this.inString = function(sub, string) {
		return string.indexOf(sub) > -1;
	}
}
