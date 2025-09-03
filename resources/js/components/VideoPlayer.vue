<template>
  <div class="video-player-container">
    
    <!-- Video Player Section -->
    <div class="video-section mb-4">
      <div class="video-container">
        <video
          ref="videoPlayer"
          class="video-js vjs-big-play-centered vjs-fluid"
          controls
          preload="auto"
          data-setup='{}'
        >
          <source :src="currentVideoUrl" type="video/mp4" />
        </video>
      </div>
      
      <div class="video-info mt-3">
        <h4>{{ videoTitle }}</h4>
        <p v-if="videoDescription" class="text-muted">{{ videoDescription }}</p>
      </div>
    </div>
    
    <div class="test-section card">
      <div class="card-header">
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">URL Input:</label>
            <div class="input-group">
              <input 
                v-model="manualUrl" 
                type="text" 
                class="form-control" 
                placeholder="Enter video URL (e.g., /storage/videos/video2.mp4)"
                @keyup.enter="loadManualUrl"
              />
              <button 
                @click="loadManualUrl" 
                class="btn btn-primary"
                :disabled="loading || !manualUrl.trim()"
              >
                Load
              </button>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Video Switcher:</label>
            <div class="d-flex flex-wrap gap-2">
              <button 
                v-for="(testVideo, index) in testVideos" 
                :key="index"
                @click="changeVideo(testVideo.url, testVideo.title, testVideo.description)"
                class="btn btn-sm"
                :class="currentVideoUrl === testVideo.url ? 'btn-primary' : 'btn-outline-secondary'"
                :disabled="loading"
              >
                {{ testVideo.title }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

export default {
  name: 'VideoPlayer',
  props: {
    videoSrc: { 
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: 'Video Player'
    },
    description: {
      type: String,
      default: ''
    },
    autoplay: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      player: null,
      loading: false,
      playerReady: false,
      currentVideoUrl: this.videoSrc || '/storage/videos/video1.mp4',
      currentTitle: this.title || 'Default Video',
      currentDescription: this.description || 'Test video from public storage',
      lastUpdated: new Date().toLocaleTimeString(),
      manualUrl: '',
      lastError: '',
      errorCount: 0,
      testVideos: [
        {
          url: '/storage/videos/video1.mp4',
          title: 'Video 1',
          description: 'First test video'
        },
        {
          url: '/storage/videos/video2.mp4',
          title: 'Video 2',
          description: 'Second test video'
        },
        {
          url: '/storage/videos/video3.mp4',
          title: 'Video 3',
          description: 'Third test video'
        },
        {
          url: '/storage/videos/video4.mp4',
          title: 'Video 4',
          description: 'Fourth test video'
        },
        {
          url: '/storage/videos/video5.mp4',
          title: 'Video 5',
          description: 'Fifth test video'
        }
      ]
    };
  },
  computed: {
    videoTitle() {
      return this.currentTitle;
    },
    videoDescription() {
      return this.currentDescription;
    }
  },
  watch: {
    videoSrc: {
      handler(newUrl) {
        if (newUrl && newUrl !== this.currentVideoUrl) {
          console.log('VideoSrc prop changed:', newUrl);
          this.changeVideo(newUrl, this.title, this.description);
        }
      },
      immediate: false
    },
    title(newTitle) {
      if (newTitle) {
        this.currentTitle = newTitle;
      }
    },
    description(newDescription) {
      if (newDescription) {
        this.currentDescription = newDescription;
      }
    }
  },
  methods: {
    initializePlayer() {
      if (this.player) {
        try {
          this.player.dispose();
        } catch (e) {
          console.warn('Error disposing player:', e);
        }
        this.player = null;
      }
      
      this.playerReady = false;
      
      this.$nextTick(() => {
        if (this.$refs.videoPlayer) {
          try {
            this.player = videojs(this.$refs.videoPlayer, {
              controls: true,
              autoplay: this.autoplay,
              preload: 'auto',
              responsive: true,
              fluid: true,
              playbackRates: [0.5, 1, 1.25, 1.5, 2],
              html5: {
                vhs: {
                  overrideNative: true
                },
                nativeVideoTracks: false,
                nativeAudioTracks: false,
                nativeTextTracks: false
              }
            });
            
            this.player.on('ready', () => {
              console.log('Player ready');
              this.playerReady = true;
              this.loading = false;
            });

            this.player.on('loadstart', () => {
              console.log('Load started for:', this.currentVideoUrl);
              this.loading = true;
              this.clearError();
            });

            this.player.on('canplay', () => {
              console.log('Can play');
              this.loading = false;
            });

            this.player.on('loadeddata', () => {
              console.log('Data loaded successfully');
              this.loading = false;
              this.lastUpdated = new Date().toLocaleTimeString();
            });

            this.player.on('error', (error) => {
              console.error('Video error:', error);
              const playerError = this.player.error();
              let errorMessage = 'Unknown error';
              
              if (playerError) {
                switch(playerError.code) {
                  case 1:
                    errorMessage = 'Video loading aborted';
                    break;
                  case 2:
                    errorMessage = 'Network error while loading video';
                    break;
                  case 3:
                    errorMessage = 'Video format not supported or corrupted';
                    break;
                  case 4:
                    errorMessage = 'Video not found or server error';
                    break;
                  default:
                    errorMessage = playerError.message || 'Video playback error';
                }
              }
              
              this.lastError = `${errorMessage} (URL: ${this.currentVideoUrl})`;
              this.errorCount++;
              this.loading = false;
              
              this.$emit('video-error', {
                error: error,
                url: this.currentVideoUrl,
                message: errorMessage
              });
            });
            
          } catch (error) {
            console.error('Error initializing player:', error);
            this.lastError = 'Failed to initialize video player';
            this.loading = false;
          }
        }
      });
    },

    async changeVideo(videoUrl, title = null, description = null) {
      if (!videoUrl) {
        console.warn('No video URL provided');
        return;
      }

      console.log('Changing video to:', videoUrl);
      this.loading = true;
      this.clearError();
      
      try {
        this.currentVideoUrl = videoUrl;
        this.currentTitle = title || `Video: ${videoUrl.split('/').pop()}`;
        this.currentDescription = description || `Playing: ${videoUrl}`;
        this.lastUpdated = new Date().toLocaleTimeString();
        
        if (this.player && this.playerReady) {
          this.player.pause();
          this.player.currentTime(0);      
            await this.validateVideoUrl(videoUrl);
          
          this.player.src([{
            type: 'video/mp4',
            src: videoUrl
          }]);
          
          this.player.load();
        } else {
          await this.$nextTick();
          this.initializePlayer();
        }

        this.$emit('video-changed', {
          url: videoUrl,
          title: this.currentTitle,
          description: this.currentDescription
        });
        
      } catch (error) {
        console.error('Error changing video:', error);
        this.lastError = `Failed to load video: ${error.message}`;
        this.errorCount++;
        this.loading = false;
        this.$emit('video-error', { error, url: videoUrl });
      }
    },

    async validateVideoUrl(url) {
      return new Promise((resolve, reject) => {
        const video = document.createElement('video');
        video.onloadedmetadata = () => resolve(true);
        video.onerror = () => reject(new Error('Invalid video URL or file not found'));
        video.src = url;
      });
    },

    loadManualUrl() {
      if (this.manualUrl.trim()) {
        let url = this.manualUrl.trim();
        
        if (!url.startsWith('http') && !url.startsWith('/')) {
          url = '/storage/videos/' + url;
        }
        
        this.changeVideo(
          url, 
          `Manual: ${url.split('/').pop()}`,
          `Manually loaded: ${url}`
        );
        this.manualUrl = '';
      }
    },

    clearError() {
      this.lastError = '';
    }
  },
  
  mounted() {
    console.log('VideoPlayer mounted with URL:', this.currentVideoUrl);
    this.initializePlayer();
  },
  
  beforeUnmount() {
    if (this.player) {
      try {
        this.player.dispose();
      } catch (e) {
        console.warn('Error disposing player on unmount:', e);
      }
    }
  }
};
</script>

<style scoped>
.video-player-container {
  position: relative;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  border-radius: 8px;
}

.video-container {
  position: relative;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
  background: #000;
  border-radius: 8px;
  overflow: hidden;
}

.video-container :deep(.video-js) {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.video-info h4 {
  color: #333;
  margin-bottom: 10px;
}

.test-section {
  border: 1px solid #e0e0e0;
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #e0e0e0;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.input-group .btn {
  border-left: none;
}

.gap-2 > * {
  margin-right: 0.5rem;
  margin-bottom: 0.5rem;
}

small {
  font-size: 0.875em;
}

.bg-light {
  background-color: #f8f9fa !important;
}

.alert-dismissible .btn-close {
  padding: 0.375rem 0.75rem;
}
</style>