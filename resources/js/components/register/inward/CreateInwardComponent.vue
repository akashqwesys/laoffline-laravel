<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Inward</h3>
                                <h3 v-else class="nk-block-title page-title">Add Inward</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->                            
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <ul class="row g-gs preview-icon-svg">
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_call">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <rect x="5" y="9" width="70" height="51.71" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M15,63.7V25.91a7,7,0,0,1,7-7H78a7,7,0,0,1,7,7V63.7a7,7,0,0,1-7,7H31L15,81Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M63,51.72v4.39a2.94,2.94,0,0,1-3,2.94h-.28A29.49,29.49,0,0,1,47,54.54,29.26,29.26,0,0,1,33.58,33.07a2.93,2.93,0,0,1,2.68-3.17l.27,0H41a3,3,0,0,1,3,3.27,3.75,3.75,0,0,0,.63,2.65,2.9,2.9,0,0,1-.27,3.8l-1.88,1.86a23.51,23.51,0,0,0,8.87,8.78l1.88-1.86a3,3,0,0,1,3.15-.65,19.64,19.64,0,0,0,4.13,1A2.93,2.93,0,0,1,63,51.72Z" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /><text transform="translate(50.23 41.17) scale(1.01 1)" font-size="14.07" fill="#9cabff" font-family="Nunito-Bold, Nunito" font-weight="700" letter-spacing="-0.05em"></text>
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Call</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_message">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Message</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_whatsapp">
                                        <div class="preview-icon-wrap">
                                            <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="90" height="90" fill="white"/>
                                                <path d="M68 9H12C8.13401 9 5 12.134 5 16V53.71C5 57.576 8.13401 60.71 12 60.71H68C71.866 60.71 75 57.576 75 53.71V16C75 12.134 71.866 9 68 9Z" fill="#E3E7FE" stroke="#6576FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15 63.7V25.91C15 24.0535 15.7375 22.273 17.0503 20.9603C18.363 19.6475 20.1435 18.91 22 18.91H78C79.8565 18.91 81.637 19.6475 82.9497 20.9603C84.2625 22.273 85 24.0535 85 25.91V63.7C85 65.5565 84.2625 67.337 82.9497 68.6498C81.637 69.9625 79.8565 70.7 78 70.7H31L15 81V63.7Z" fill="white" stroke="#6576FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M56.415 47.382C56.117 47.233 54.656 46.515 54.384 46.415C54.112 46.316 53.914 46.267 53.715 46.565C53.517 46.861 52.948 47.531 52.775 47.729C52.601 47.928 52.428 47.952 52.131 47.804C51.834 47.654 50.876 47.341 49.741 46.329C48.858 45.541 48.261 44.568 48.088 44.27C47.915 43.973 48.069 43.812 48.218 43.664C48.352 43.531 48.515 43.317 48.664 43.144C48.813 42.97 48.862 42.846 48.961 42.647C49.061 42.449 49.011 42.276 48.936 42.127C48.862 41.978 48.268 40.515 48.02 39.92C47.779 39.341 47.534 39.42 47.352 39.41C47.178 39.402 46.98 39.4 46.782 39.4C46.584 39.4 46.262 39.474 45.99 39.772C45.717 40.069 44.95 40.788 44.95 42.251C44.95 43.713 46.014 45.126 46.163 45.325C46.312 45.523 48.258 48.525 51.239 49.812C51.949 50.118 52.502 50.301 52.933 50.437C53.645 50.664 54.293 50.632 54.805 50.555C55.375 50.47 56.563 49.836 56.811 49.142C57.058 48.448 57.058 47.853 56.984 47.729C56.91 47.605 56.712 47.531 56.414 47.382H56.415ZM50.993 54.785H50.989C49.2184 54.7853 47.4804 54.3094 45.957 53.407L45.597 53.193L41.855 54.175L42.854 50.527L42.619 50.153C41.6291 48.5773 41.1053 46.7538 41.108 44.893C41.11 39.443 45.544 35.009 50.997 35.009C53.637 35.009 56.119 36.039 57.985 37.907C58.9054 38.8236 59.6349 39.9136 60.1313 41.1139C60.6277 42.3142 60.8811 43.6011 60.877 44.9C60.875 50.35 56.441 54.785 50.993 54.785V54.785ZM59.405 36.488C58.3032 35.3789 56.9922 34.4995 55.5481 33.9008C54.1039 33.3021 52.5553 32.9959 50.992 33C44.438 33 39.102 38.335 39.1 44.892C39.097 46.9787 39.6444 49.0294 40.687 50.837L39 57L45.304 55.346C47.0479 56.2961 49.0021 56.794 50.988 56.794H50.993C57.547 56.794 62.883 51.459 62.885 44.901C62.8898 43.3383 62.5848 41.7901 61.9874 40.346C61.3901 38.902 60.5124 37.5907 59.405 36.488" fill="#E3E7FE"/>
                                                <path d="M59.405 36.488C58.3032 35.3789 56.9922 34.4995 55.5481 33.9008C54.1039 33.3021 52.5553 32.9959 50.992 33C44.438 33 39.102 38.335 39.1 44.892C39.097 46.9787 39.6444 49.0294 40.687 50.837L39 57L45.304 55.346C47.0479 56.2961 49.0021 56.794 50.988 56.794H50.993C57.547 56.794 62.883 51.459 62.885 44.901C62.8898 43.3383 62.5848 41.7901 61.9874 40.346C61.3901 38.902 60.5124 37.5907 59.405 36.488M56.415 47.382C56.117 47.233 54.656 46.515 54.384 46.415C54.112 46.316 53.914 46.267 53.715 46.565C53.517 46.861 52.948 47.531 52.775 47.729C52.601 47.928 52.428 47.952 52.131 47.804C51.834 47.654 50.876 47.341 49.741 46.329C48.858 45.541 48.261 44.568 48.088 44.27C47.915 43.973 48.069 43.812 48.218 43.664C48.352 43.531 48.515 43.317 48.664 43.144C48.813 42.97 48.862 42.846 48.961 42.647C49.061 42.449 49.011 42.276 48.936 42.127C48.862 41.978 48.268 40.515 48.02 39.92C47.779 39.341 47.534 39.42 47.352 39.41C47.178 39.402 46.98 39.4 46.782 39.4C46.584 39.4 46.262 39.474 45.99 39.772C45.717 40.069 44.95 40.788 44.95 42.251C44.95 43.713 46.014 45.126 46.163 45.325C46.312 45.523 48.258 48.525 51.239 49.812C51.949 50.118 52.502 50.301 52.933 50.437C53.645 50.664 54.293 50.632 54.805 50.555C55.375 50.47 56.563 49.836 56.811 49.142C57.058 48.448 57.058 47.853 56.984 47.729C56.91 47.605 56.712 47.531 56.414 47.382H56.415ZM50.993 54.785H50.989C49.2184 54.7853 47.4804 54.3094 45.957 53.407L45.597 53.193L41.855 54.175L42.854 50.527L42.619 50.153C41.6291 48.5773 41.1053 46.7538 41.108 44.893C41.11 39.443 45.544 35.009 50.997 35.009C53.637 35.009 56.119 36.039 57.985 37.907C58.9054 38.8236 59.6349 39.9136 60.1313 41.1139C60.6277 42.3142 60.8811 43.6011 60.877 44.9C60.875 50.35 56.441 54.785 50.993 54.785V54.785Z" stroke="#6576FF"/>
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Whatsapp</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_email">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Email</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_letter">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <rect x="15" y="5" width="56" height="70" rx="6" ry="6" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M69.88,85H30.12A6.06,6.06,0,0,1,24,79V21a6.06,6.06,0,0,1,6.12-6H59.66L76,30.47V79A6.06,6.06,0,0,1,69.88,85Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <polyline points="60 16 60 31 75 31.07" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="69" y1="47" x2="31" y2="47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="69" y1="53" x2="31" y2="53" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="69" y1="59" x2="31" y2="59" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="69" y1="65" x2="31" y2="65" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="69" y1="71" x2="31" y2="71" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Letter</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-4 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="inward_sample">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 118">
                                                <path d="M8.916,94.745C-.318,79.153-2.164,58.569,2.382,40.578,7.155,21.69,19.045,9.451,35.162,4.32,46.609.676,58.716.331,70.456,1.845,84.683,3.68,99.57,8.694,108.892,21.408c10.03,13.679,12.071,34.71,10.747,52.054-1.173,15.359-7.441,27.489-19.231,34.494-10.689,6.351-22.92,8.733-34.715,10.331-16.181,2.192-34.195-.336-47.6-12.281A47.243,47.243,0,0,1,8.916,94.745Z" transform="translate(0 -1)" fill="#f6faff" />
                                                <rect x="18" y="32" width="84" height="50" rx="4" ry="4" fill="#fff" />
                                                <rect x="26" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe" />
                                                <rect x="50" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe" />
                                                <rect x="74" y="44" width="20" height="12" rx="1" ry="1" fill="#e5effe" />
                                                <rect x="38" y="60" width="20" height="12" rx="1" ry="1" fill="#e5effe" />
                                                <rect x="62" y="60" width="20" height="12" rx="1" ry="1" fill="#e5effe" />
                                                <path d="M98,32H22a5.006,5.006,0,0,0-5,5V79a5.006,5.006,0,0,0,5,5H52v8H45a2,2,0,0,0-2,2v4a2,2,0,0,0,2,2H73a2,2,0,0,0,2-2V94a2,2,0,0,0-2-2H66V84H98a5.006,5.006,0,0,0,5-5V37A5.006,5.006,0,0,0,98,32ZM73,94v4H45V94Zm-9-2H54V84H64Zm37-13a3,3,0,0,1-3,3H22a3,3,0,0,1-3-3V37a3,3,0,0,1,3-3H98a3,3,0,0,1,3,3Z" transform="translate(0 -1)" fill="#798bff" />
                                                <path d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z" transform="translate(0 -1)" fill="#6576ff" />
                                                <path d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z" transform="translate(0 -1)" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2" />
                                                <line x1="40" y1="22" x2="57" y2="22" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="40" y1="27" x2="57" y2="27" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="40" y1="32" x2="50" y2="32" fill="none" stroke="#fffffe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="30.5" y1="87.5" x2="30.5" y2="91.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="28.5" y1="89.5" x2="32.5" y2="89.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="79.5" y1="22.5" x2="79.5" y2="26.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="77.5" y1="24.5" x2="81.5" y2="24.5" fill="none" stroke="#9cabff" stroke-linecap="round" stroke-linejoin="round" />
                                                <circle cx="90.5" cy="97.5" r="3" fill="none" stroke="#9cabff" stroke-miterlimit="10" />
                                                <circle cx="24" cy="23" r="2.5" fill="none" stroke="#9cabff" stroke-miterlimit="10" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Sample</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                        </ul>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var agents = [];
    export default {
        name: 'createInward',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/register',
                inward_call: '/register/inward/call',
                inward_message: '/register/inward/message',
                inward_whatsapp: '/register/inward/whatsapp',
                inward_letter: '/register/inward/letter',
                inward_sample: '/register/inward/sample',
                inward_email: '/register/inward/email',
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/settings/agent/update')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                } else {
                    this.form.post('/settings/agent/create')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/agent/fetch-agent/${this.id}`)
                    .then(response => {
                        agents = response.data;

                        this.form.id = agents.id;
                        this.form.name = agents.name;
                        this.form.pan_no = agents.pan_no;
                        this.form.gst_no = agents.gst_no;
                        this.form.default = agents.default;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>