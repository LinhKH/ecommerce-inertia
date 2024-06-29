import { StrictMode, useEffect } from 'react';
import Header from '../Components/Header'
import Footer from '../Components/Footer'
//import { usePage } from '@inertiajs/react'

export default function Layout({children}) {
    return (
        <div>
            <StrictMode>
                <Header/>
                {/* <main className='container'> */}
                    {children}
                {/* </main> */}
                <Footer/>
            </StrictMode>
        </div>
    )
}