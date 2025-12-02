<template>
  <Popper click placement="bottom" @close:popper="onPopperClosed">
    <i role="button" class="mdi mdi-information primary_bg map_primary_small_icon p-0" id="show_hide_route_import_info_button"></i>

    <template #content>
      <table class="table table-borderless scrollbar scrollbar-deep-blue mb-0">
        <tr v-for="(groupe, index) in localGroups" :key="groupe.id ?? index">
          <th class="p-1 col-sm-7">
            <span @click="$emit('filter', groupe)" role="button">{{ groupe.column_fullname }} :</span>
          </th>

          <td class="p-1 col-sm-3"><span>{{ groupe.clients?.length ?? 0 }} Clients</span></td>

          <td class="p-1 col-sm-1">
            <div class="color-picker-wrapper" style="position:relative; display:inline-block;">
              <span
                class="color-swatch"
                :style="{
                  display: 'inline-block',
                  width: '15px',
                  height: '15px',
                  float: 'right',
                  backgroundColor: groupe.color || '#000'
                }"
                role="button"
                :aria-label="`Open color picker for ${groupe.column_fullname}`"
                tabindex="0"
                :data-swatch-index="index"
                :ref="'swatch-' + index"
                @click.stop="togglePalette(index, $event)"
                @keydown.enter.prevent.stop="togglePalette(index, $event)"
                @keydown.space.prevent.stop="togglePalette(index, $event)"
              ></span>

              <!-- Teleport the palette into body to avoid clipping by Popper/containers -->
              <teleport to="body">
                <div
                  v-if="openPaletteFor === index"
                  class="color-palette-popup"
                  :data-palette-index="index"
                  :style="{ top: popupPositions[index]?.top + 'px', left: popupPositions[index]?.left + 'px' }"
                  @click.stop
                  role="dialog"
                  :aria-label="`Color palette for ${groupe.column_fullname}`"
                >
                  <div class="palette-header">
                    <div class="palette-title">Couleur</div>
                  </div>

                  <div class="color-grid" role="list">
                    <button
                      v-for="c in $map.colors"
                      :key="c"
                      class="color-btn"
                      :class="{ selected: (localGroups[index]?.color || '') === c }"
                      :style="{ backgroundColor: c }"
                      :title="c"
                      tabindex="0"

                      @click.prevent="selectColor(index, c)"
                      @keydown.enter.prevent="selectColor(index, c)"
                      @pointerdown.prevent="onPalettePointerDown(index, c, $event)"

                      :aria-label="`Select color ${c}`"
                      role="listitem"
                    >
                      <span v-if="(localGroups[index]?.color || '') === c" class="selected-indicator"></span>
                    </button>
                  </div>
                </div>
              </teleport>
            </div>
          </td>
        </tr>
      </table>
    </template>
  </Popper>
</template>

<script>
export default {
  name: 'ColorRowsComponent',

  props: {
    clients_markers_affiche: {
      type: [Array, Object],
      required: true
    }
  },

  data() {
    return {
      openPaletteFor: null,
      localGroups: [],

      // map index -> { top: number, left: number }
      popupPositions: {},

      // offsets you requested (top: 0px, left: 30px relative to swatch)
      leftOffset: 30,
      topOffset: 0
    }
  },

  emits: ['color-changed', 'filter'],

  created() {
    this.normalizeGroups()
  },

  watch: {
    clients_markers_affiche: {
      handler() {
        this.normalizeGroups()
      },
      deep: true
    }
  },

  methods: {
    normalizeGroups() {
      const val = this.clients_markers_affiche || []
      if (Array.isArray(val)) {
        this.localGroups = val.map(g => ({ ...g }))
      } else {
        this.localGroups = Object.values(val).map(g => ({ ...g }))
      }
    },

    // Open/close the palette and compute its viewport position
    togglePalette(index, event) {
      if (event && event.stopPropagation) event.stopPropagation()

      // if closing same index:
      if (this.openPaletteFor === index) {
        this.openPaletteFor = null
        return
      }

      // open requested index
      this.openPaletteFor = index

      // compute position for the palette relative to the swatch (viewport coords)
      this.$nextTick(() => {
        // use the swatch element to compute position
        const swatch = this.$refs['swatch-' + index]
        // refs from v-for with unique string refs should be single element
        const swatchEl = Array.isArray(swatch) ? swatch[0] : swatch
        if (!swatchEl) {
          // fallback to searching by data attribute (shouldn't be necessary)
          const fallback = document.querySelector(`[data-swatch-index="${index}"]`)
          if (fallback) {
            this.setPopupPositionFromRect(fallback.getBoundingClientRect(), index)
          }
          return
        }
        const rect = swatchEl.getBoundingClientRect()
        this.setPopupPositionFromRect(rect, index)
      })
    },

    setPopupPositionFromRect(rect, index) {
      // We teleport the popup to body and position it fixed using viewport coords (rect)
      // The user wanted top:0px and left:30px relative to swatch, so we use offsets
      const top = Math.round(rect.top + this.topOffset)
      const left = Math.round(rect.left + this.leftOffset)
      this.$set ? this.$set(this.popupPositions, index, { top, left }) : (this.popupPositions[index] = { top, left })
    },

    selectColor(index, color) {

      const grp = this.localGroups[index]
      if (!grp) return

      // update UI immediately
      if (this.$set) {
        try { this.$set(this.localGroups[index], 'color', color) } catch { this.localGroups[index].color = color }
      } else {
        this.localGroups[index].color = color
      }

      this.$emit('color-changed', {
        index,
        // groupeId: grp.id ?? null,
        color
      })

      this.openPaletteFor = null
    },

    onPalettePointerDown(index, color, ev) {

      // prevent the click from bubbling to document
      ev.stopPropagation && ev.stopPropagation()
      // do the select immediately (before the Popper's click-away runs)
      this.selectColor(index, color)
    },

    handleDocumentClick(e) {
      // close if click outside both the swatch and the palette
      if (this.openPaletteFor === null) return

      const idx = this.openPaletteFor
      const clickedInPalette = !!e.target.closest(`[data-palette-index="${idx}"]`)
      const clickedInSwatch = !!e.target.closest(`[data-swatch-index="${idx}"]`)

      if (!clickedInPalette && !clickedInSwatch) {
        this.openPaletteFor = null
      }
    },

    handleKeydown(e) {
      if (e.key === 'Escape') {
        this.openPaletteFor = null
      }
    },

    onPopperClosed() {
      this.openPaletteFor = null
      this.popupPositions = {}
    }
  },

  mounted() {
    document.addEventListener('click', this.handleDocumentClick, true)
    document.addEventListener('keydown', this.handleKeydown, true)
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleDocumentClick, true)
    document.removeEventListener('keydown', this.handleKeydown, true)
  }
}
</script>

<style scoped>
/* popup container (teleported to body) */
.color-palette-popup {
  position: fixed; /* viewport-based positioning so teleport works reliably */
  z-index: 2200;
  width: auto;
  min-width: 220px;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.18);
  border: 1px solid rgba(0,0,0,0.08);
  overflow: visible;
  transform-origin: top left;
  transition: transform .06s ease, opacity .06s ease;
  /* small nudge so popup doesn't overlap swatch exactly (optional) */
}

/* header with title + close button */
.palette-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 6px 8px;
  border-bottom: 1px solid rgba(0,0,0,0.06);
  background: #f5f5f5;
  border-top-left-radius: 6px;
  border-top-right-radius: 6px;
}

.palette-title {
  font-size: 12px;
  font-weight: 600;
  color: #333;
  padding-left: 2px;
}

/* grid: 8 columns per your request */
.color-grid {
  display: grid;
  grid-template-columns: repeat(8, 20px);
  gap: 6px;
  padding: 8px;
  justify-content: start;
  align-items: center;
}

/* each color button - base small size */
.color-btn {
  width: 18px;
  height: 18px;
  padding: 0;
  border: 1px solid rgba(0,0,0,0.06);
  border-radius: 2px;
  cursor: pointer;
  position: relative;
  outline: none;
  box-sizing: border-box;
  background-clip: padding-box;

  /* enable smooth scale on hover */
  transition: transform .08s ease, box-shadow .08s ease;
  transform-origin: center;
}

/* hover: enlarge like in your screenshot and bring to front */
.color-btn:hover {
  transform: scale(1.45);
  z-index: 9999;
  box-shadow: 0 6px 14px rgba(0,0,0,0.18);
}

/* selected state: black outline and small inner square */
.color-btn.selected {
  box-shadow: 0 0 0 2px #000 inset;
}

/* small inner indicator */
/* .selected-indicator {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  display: block;
  border: 2px solid #000;
  background: #fff;
  box-sizing: border-box;
  border-radius: 1px;
} */

/* keyboard focus ring */
.color-btn:focus {
  box-shadow: 0 0 0 3px rgba(0,0,0,0.12);
  transform: scale(1.25);
}

/* swatch preview */
.color-swatch {
  cursor: pointer;
  border: 1px solid rgba(0,0,0,0.12);
  border-radius: 2px;
}

/* responsive tweak */
@media (max-width: 380px) {
  .color-grid { grid-template-columns: repeat(6, 18px); }
}
</style>
