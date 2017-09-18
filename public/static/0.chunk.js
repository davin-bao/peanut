webpackJsonp([0],{

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(8)(
  /* script */
  __webpack_require__(16),
  /* template */
  __webpack_require__(17),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\www\\peanut\\app\\Themes\\Default\\vues\\index.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] index.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2f022216", Component.options)
  } else {
    hotAPI.reload("data-v-2f022216", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 16:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            movieList: [{
                name: '肖申克的救赎',
                url: 'https://movie.douban.com/subject/1292052/',
                rate: 9.6
            }, {
                name: '这个杀手不太冷',
                url: 'https://movie.douban.com/subject/1295644/',
                rate: 9.4
            }, {
                name: '霸王别姬',
                url: 'https://movie.douban.com/subject/1291546/',
                rate: 9.5
            }, {
                name: '阿甘正传',
                url: 'https://movie.douban.com/subject/1292720/',
                rate: 9.4
            }, {
                name: '美丽人生',
                url: 'https://movie.douban.com/subject/1292063/',
                rate: 9.5
            }, {
                name: '千与千寻',
                url: 'https://movie.douban.com/subject/1291561/',
                rate: 9.2
            }, {
                name: '辛德勒的名单',
                url: 'https://movie.douban.com/subject/1295124/',
                rate: 9.4
            }, {
                name: '海上钢琴师',
                url: 'https://movie.douban.com/subject/1292001/',
                rate: 9.2
            }, {
                name: '机器人总动员',
                url: 'https://movie.douban.com/subject/2131459/',
                rate: 9.3
            }, {
                name: '盗梦空间',
                url: 'https://movie.douban.com/subject/3541415/',
                rate: 9.2
            }],
            randomMovieList: [],
            randomMovieList1: []
        };
    },

    methods: {
        changeLimit: function changeLimit() {
            function getArrayItems(arr, num) {
                var temp_array = [];
                for (var index in arr) {
                    temp_array.push(arr[index]);
                }
                var return_array = [];
                for (var i = 0; i < num; i++) {
                    if (temp_array.length > 0) {
                        var arrIndex = Math.floor(Math.random() * temp_array.length);
                        return_array[i] = temp_array[arrIndex];
                        temp_array.splice(arrIndex, 1);
                    } else {
                        break;
                    }
                }
                return return_array;
            }
            this.randomMovieList = getArrayItems(this.movieList, 5);
            this.randomMovieList1 = getArrayItems(this.movieList, 5);
        }
    },
    mounted: function mounted() {
        this.changeLimit();
    }
});

/***/ }),

/***/ 17:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('Row', {
    attrs: {
      "gutter": 16
    }
  }, [_c('Col', {
    attrs: {
      "span": "8"
    }
  }, [_c('Card', [_c('p', {
    slot: "title"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-film-outline"
    }
  }), _vm._v("\n                经典电影\n            ")], 1), _vm._v(" "), _c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeLimit($event)
      }
    },
    slot: "extra"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-loop-strong"
    }
  }), _vm._v("\n                换一换\n            ")], 1), _vm._v(" "), _c('ul', _vm._l((_vm.randomMovieList), function(item) {
    return _c('li', [_c('a', {
      attrs: {
        "href": item.url,
        "target": "_blank"
      }
    }, [_vm._v(_vm._s(item.name))]), _vm._v(" "), _c('span', [_vm._l((4), function(n) {
      return _c('Icon', {
        key: n,
        attrs: {
          "type": "ios-star"
        }
      })
    }), (item.rate >= 9.5) ? _c('Icon', {
      attrs: {
        "type": "ios-star"
      }
    }) : _c('Icon', {
      attrs: {
        "type": "ios-star-half"
      }
    }), _vm._v("\n                    " + _vm._s(item.rate) + "\n                ")], 2)])
  }))])], 1), _vm._v(" "), _c('Col', {
    attrs: {
      "span": "8"
    }
  }, [_c('Card', [_c('p', {
    slot: "title"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-film-outline"
    }
  }), _vm._v("\n            经典电影\n        ")], 1), _vm._v(" "), _c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeLimit($event)
      }
    },
    slot: "extra"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-loop-strong"
    }
  }), _vm._v("\n            换一换\n        ")], 1), _vm._v(" "), _c('ul', _vm._l((_vm.randomMovieList1), function(item) {
    return _c('li', [_c('a', {
      attrs: {
        "href": item.url,
        "target": "_blank"
      }
    }, [_vm._v(_vm._s(item.name))]), _vm._v(" "), _c('span', [_vm._l((4), function(n) {
      return _c('Icon', {
        key: n,
        attrs: {
          "type": "ios-star"
        }
      })
    }), (item.rate >= 9.5) ? _c('Icon', {
      attrs: {
        "type": "ios-star"
      }
    }) : _c('Icon', {
      attrs: {
        "type": "ios-star-half"
      }
    }), _vm._v("\n                    " + _vm._s(item.rate) + "\n                ")], 2)])
  }))])], 1), _vm._v(" "), _c('Col', {
    attrs: {
      "span": "8"
    }
  }, [_c('Card', [_c('p', {
    slot: "title"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-film-outline"
    }
  }), _vm._v("\n            经典电影\n        ")], 1), _vm._v(" "), _c('a', {
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        _vm.changeLimit($event)
      }
    },
    slot: "extra"
  }, [_c('Icon', {
    attrs: {
      "type": "ios-loop-strong"
    }
  }), _vm._v("\n            换一换\n        ")], 1), _vm._v(" "), _c('ul', _vm._l((_vm.randomMovieList1), function(item) {
    return _c('li', [_c('a', {
      attrs: {
        "href": item.url,
        "target": "_blank"
      }
    }, [_vm._v(_vm._s(item.name))]), _vm._v(" "), _c('span', [_vm._l((4), function(n) {
      return _c('Icon', {
        key: n,
        attrs: {
          "type": "ios-star"
        }
      })
    }), (item.rate >= 9.5) ? _c('Icon', {
      attrs: {
        "type": "ios-star"
      }
    }) : _c('Icon', {
      attrs: {
        "type": "ios-star-half"
      }
    }), _vm._v("\n                    " + _vm._s(item.rate) + "\n                ")], 2)])
  }))])], 1)], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-2f022216", module.exports)
  }
}

/***/ })

});