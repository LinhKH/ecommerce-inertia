import React from 'react';
import {usePage} from '@inertiajs/react';
import {baseUrl} from '../Components/Baseurl';
import { Swiper, SwiperSlide } from 'swiper/react';
// Import Swiper styles
import 'swiper/css';

function Banner() {
    const { banner } = usePage().props;

    return (
      <div id="bannerSlider">
      <Swiper>
        {banner.map((item) => (
          item.status == '1' && (
            <SwiperSlide>
              <img className="w-100" src={baseUrl+'/public/banner/' + item.banner_img} />
            </SwiperSlide>
          )
        ))} 
      </Swiper>
      </div>
    )
}
export default Banner