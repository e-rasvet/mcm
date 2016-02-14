// Generated by CoffeeScript 1.6.1
(function() {

  (function() {
    var DEBUG, DragDrop, ERROR, INFO, LOG_LEVEL, VERBOSE, average, coordinateSystemForElementFromPoint, div, doc, dragDiv, dragstart, elementFromTouchEvent, evts, getEls, handler, log, needsPatch, onEvt, once, parents,
      _this = this;
    VERBOSE = 3;
    DEBUG = 2;
    INFO = 1;
    ERROR = 0;
    LOG_LEVEL = DEBUG;
    doc = document;
    log = function(msg, level) {
      if (level == null) {
        level = ERROR;
      }
      if (level <= LOG_LEVEL) {
        return console.log(msg);
      }
    };
    log = function() {};
    onEvt = function(el, event, handler) {
      el.addEventListener(event, handler);
      return {
        off: function() {
          return el.removeEventListener(event, handler);
        }
      };
    };
    once = function(el, event, handler) {
      var listener;
      return el.addEventListener(event, listener = function(evt) {
        handler(evt);
        return el.removeEventListener(event, listener);
      });
    };
    average = function(arr) {
      if (arr.length === 0) {
        return 0;
      }
      return arr.reduce((function(s, v) {
        return v + s;
      }), 0) / arr.length;
    };
    coordinateSystemForElementFromPoint = navigator.userAgent.match(/OS 5(?:_\d+)+ like Mac/) ? "client" : "page";
    elementFromTouchEvent = function(event) {
      var touch;
      touch = event.changedTouches[0];
      log("touch");
      return doc.elementFromPoint(touch[coordinateSystemForElementFromPoint + "X"], touch[coordinateSystemForElementFromPoint + "Y"]);
    };
    DragDrop = (function() {

      function DragDrop(event, el) {
        var cancel, cleanup, end, evt, match, move, transform, x, y, _ref,
          _this = this;
        if (el == null) {
          el = event.target;
        }
        this.dragend = function(event) {
          return DragDrop.prototype.dragend.apply(_this, arguments);
        };
        this.move = function(event) {
          return DragDrop.prototype.move.apply(_this, arguments);
        };
        event.preventDefault();
        log("dragstart");
        this.dragData = {};
        evt = doc.createEvent("Event");
        evt.initEvent("dragstart", true, true);
        evt.dataTransfer = {
          setData: function(type, val) {
            return _this.dragData[type] = val;
          },
          dropEffect: "move"
        };
        el.dispatchEvent(evt);
        cleanup = function() {
          log("cleanup");
          _this.touchPositions = {};
          return [move, end, cancel].forEach(function(handler) {
            return handler.off();
          });
        };
        this.el = el;
        this.touchPositions = {};
        transform = this.el.style["-webkit-transform"];
        _ref = (match = /translate\(\s*(\d+)[^,]*,\D*(\d+)/.exec(transform)) ? [parseInt(match[1]), parseInt(match[2])] : [0, 0], x = _ref[0], y = _ref[1];
        this.elTranslation = {
          x: x,
          y: y
        };
        move = onEvt(doc, "touchmove", this.move);
        end = onEvt(doc, "touchend", function(evt) {
          _this.dragend(evt, event.target);
          return cleanup();
        });
        cancel = onEvt(doc, "touchcancel", cleanup);
      }

      DragDrop.prototype.move = function(event) {
        var deltas,
          _this = this;
        log("dragmove", VERBOSE);
        deltas = [].slice.call(event.changedTouches).reduce(function(deltas, touch, index) {
          var position;
          position = _this.touchPositions[index];
          if (position) {
            deltas.x.push(touch.pageX - position.x);
            deltas.y.push(touch.pageY - position.y);
          } else {
            _this.touchPositions[index] = position = {};
          }
          position.x = touch.pageX;
          position.y = touch.pageY;
          return deltas;
        }, {
          x: [],
          y: []
        });
        this.elTranslation.x += average(deltas.x);
        this.elTranslation.y += average(deltas.y);
        return this.el.style["-webkit-transform"] = "translate(" + this.elTranslation.x + "px," + this.elTranslation.y + "px)";
      };

      DragDrop.prototype.dragend = function(event) {
        var doSnapBack, dragendEvt, dropEvt, next, parent, snapBack, target,
          _this = this;
        log("dragend");
        doSnapBack = function() {
          once(_this.el, "webkitTransitionEnd", function() {
            return _this.el.style["-webkit-transition"] = "none";
          });
          return setTimeout(function() {
            _this.el.style["-webkit-transition"] = "all 0.2s";
            return _this.el.style["-webkit-transform"] = "translate(0,0)";
          });
        };
        parent = this.el.parentElement;
        parent.removeChild(this.el);
        target = elementFromTouchEvent(event);
        if (next = this.el.nextSibling) {
          parent.insertBefore(this.el, next);
        } else {
          parent.appendChild(this.el);
        }
        if (target) {
          log("Found drop target " + target.tagName);
          dropEvt = doc.createEvent("Event");
          dropEvt.initEvent("drop", true, true);
          dropEvt.dataTransfer = {
            getData: function(type) {
              return _this.dragData[type];
            }
          };
          snapBack = true;
          dropEvt.preventDefault = function() {
            snapBack = false;
            return _this.el.style["-webkit-transform"] = "translate(0,0)";
          };
          once(doc, "drop", function() {
            log("drop event not canceled");
            if (snapBack) {
              return doSnapBack();
            }
          });
          target.dispatchEvent(dropEvt);
        } else {
          once(doc, "dragend", doSnapBack);
        }
        dragendEvt = doc.createEvent("Event");
        dragendEvt.initEvent("dragend", true, true);
        return this.el.dispatchEvent(dragendEvt);
      };

      return DragDrop;

    })();
    getEls = function(el, selector) {
      var _ref;
      if (!selector) {
        _ref = [doc, el], el = _ref[0], selector = _ref[1];
      }
      return [].slice.call(el.querySelectorAll(selector));
    };
    div = doc.createElement('div');
    dragDiv = 'draggable' in div;
    evts = 'ondragstart' in div && 'ondrop' in div;
    needsPatch = !(dragDiv || evts) || /iPad|iPhone|iPod/.test(navigator.userAgent);
    log("" + (needsPatch ? "" : "not ") + "patching html5 drag drop");
    if (!needsPatch) {
      return;
    }
    dragstart = function(evt) {
      evt.preventDefault();
      return new DragDrop(evt);
    };
    parents = function(el) {
      var parent, _results;
      _results = [];
      while ((parent = el.parentNode) && parent !== doc.body) {
        el = parent;
        _results.push(parent);
      }
      return _results;
    };
    return doc.addEventListener("touchstart", handler = function(evt) {
      var el, _i, _len, _ref;
      _ref = [evt.target].concat(parents(evt.target));
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        el = _ref[_i];
        if (el.hasAttribute("draggable")) {
          evt.preventDefault();
          return dragstart(evt, el);
        }
      }
      return null;
    });
  })();

}).call(this);