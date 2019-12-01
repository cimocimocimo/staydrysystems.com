import $ from 'jquery'
import TweenMax from 'gsap/TweenMax'
// import TimelineLite from 'gsap/TimelineLite'
// import 'gsap/CSSPlugin' // only needed if using TweenLite
// import 'gsap/EasePack'
import ScrollMagic from 'scrollmagic/scrollmagic/uncompressed/ScrollMagic'
import ScrollToPlugin from 'gsap/ScrollToPlugin'

var heroAnimation = {
  timelines: {
    mobile: {
      main: null,
      features: {
        problems: null,
        solutions: null,
      }
    },
    desktop: {
      main: null,
      features: {
        problems: null,
        solutions: null,
      }
    },
  },

  createFeatureTimeline ($features, mainTimeline) {
    var timeline = new TimelineMax({paused: true});

    timeline.add(function () {
      mainTimeline.pause();
    }).staggerFromTo(
      $features, 1,
      {scale: .5, y: '-=40px',autoAlpha: 0},
      {scale: 1, y: 0, autoAlpha: 1, ease:Back.easeOut},
      0.25
    ).add(function () {
      mainTimeline.play();
    }).pause();

    return timeline;
  },

  wirePanelClickHandlers ($problem, $solution, timeline) {
    $problem.click(function () {
      timeline.tweenTo('swapStart');
    });
    $solution.click(function () {
      timeline.tweenTo('swapEnd');
    });
  },

  initTimeline ($stage, isDesktop = false) {
    var $panels = $stage.find('.panel__problem, .panel__solution'),
        $problem = $stage.find('.panel__problem'),
        $solution = $stage.find('.panel__solution'),
        $problemFeatures = $stage.find('.problem-feature'),
        $solutionFeatures = $stage.find('.solution-feature'),
        timeline = new TimelineMax({paused: true});

    var problemFeaturesTimeline = this.createFeatureTimeline($problemFeatures, timeline);
    var solutionFeaturesTimeline = this.createFeatureTimeline($solutionFeatures, timeline);

    timeline
      .add('start')
      .to(
        $problem,
        0.5,
        {scale:0.9}, 'start'
      )
      .to(
        $solution,
        0.5,
        {scale:0.7}, 'start'
      )
      .to(
        $panels,
        0.5,
        {autoAlpha: 1}, 'start'
      )
      .add(function () {
        problemFeaturesTimeline.play()
      });

    if (isDesktop) {
      timeline
        .add('swapStart')
        .add('swapMid', '+=0.75')
        .to($problem, 1.5, {scale: 0.7, left: '0%', ease: Circ.easeInOut}, 'swapStart')
        .to($solution, 1.5, {scale: 0.9, right: '0%', ease: Circ.easeInOut}, 'swapStart')
        .to($problem, 0.75, {left: '-2%', ease: Power2.easeIn}, 'swapStart')
        .to($solution, 0.75, {right: '-2%', ease: Power2.easeIn}, 'swapStart')
        .set($problem, {zIndex: 0}, 'swapMid')
        .set($solution, {zIndex: 10}, 'swapMid')
        .to($problem, 0.75, {left: '0%', ease: Power2.easeOut}, 'swapMid')
        .to($solution, 0.75, {right: '0%', ease: Power2.easeOut}, 'swapMid')
        .add(function () {
          solutionFeaturesTimeline.play()
        })
        .add('swapEnd');
    } else {
      timeline
        .add('swapStart')
        .to($problem, 1.5, {scale: 0.65, left: '-5%', ease: Circ.easeInOut}, 'swapStart')
        .to($solution, 1.5, {scale: 0.65, right: '-5%', ease: Circ.easeInOut}, 'swapStart')
        .add('swapMid')
        .set($problem, {zIndex: 0})
        .set($solution, {zIndex: 10})
        .to($problem, 1.5, {scale: 0.8, left: '0%', ease: Circ.easeInOut}, 'swapMid')
        .to($solution, 1.5, {scale: 1, right: '0%', ease: Circ.easeInOut}, 'swapMid')
        .add(function () {
          solutionFeaturesTimeline.play()
        })
        .add('swapEnd')
        .call(this.wirePanelClickHandlers, [$problem, $solution, timeline]);
    }

    return {
      main: timeline,
      features: {
        problems: problemFeaturesTimeline,
        solutions: solutionFeaturesTimeline
      }
    };
  },

  init () {
    var self = this,
        $hero = $('.homepage-hero'),
        $mobileStage = $('.homepage-hero__stage-mobile'),
        $desktopStage = $('.homepage-hero__stage-desktop');

    this.timelines.mobile = this.initTimeline($mobileStage);
    this.timelines.desktop = this.initTimeline($desktopStage, true);

    $(window).load(function(){
      self.timelines.mobile.main.play()
      self.timelines.desktop.main.play()
    });
  },
};

export default {
  init() {

    heroAnimation.init();

    // init controller
    var controller = new ScrollMagic.Controller();
    var pageHeight = $('#page').height();
    $(window).on('resize', function() {
      pageHeight = $('#page').height();
    });
    new ScrollMagic.Scene({
      triggerElement: '#page',
      duration: pageHeight})
      .addTo(controller)
      .on("update", function (e) {
        console.log(e.target.controller().info("scrollDirection"));
      })
      .on("enter leave", function (e) {
        console.log(e.type == "enter" ? "inside" : "outside");
      })
      .on("start end", function (e) {
        console.log(e.type == "start" ? "top" : "bottom");
      })
      .on("progress", function (e) {
        console.log(e.progress)
      });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
