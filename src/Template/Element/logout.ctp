<style type="text/css">
    #box{
        padding:50px;
        position: absolute;
        left: 50%;
        top: 50%;

        /*
        *  Where the magic happens
        *  Centering method from CSS Tricks
        *  http://css-tricks.com/centering-percentage-widthheight-elements/
        */
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
        background-color: white;
    }

    body{
        background-color: #e8f0f5;
        font-family: 'PT Sans', sans-serif;
        font-weight:300;
    }

    h1, h2, h3, h4, h5, h6{
        font-family: 'PT Sans', sans-serif;
    }

    .error{
        color: #C3232D;
    }

    div.message:before{
        display:none;
    }

    div.message {
        text-align: center;
        background: none;
        cursor: pointer;
        display: block;
        font-weight: normal;
        padding: 0 1.5rem 0 1.5rem;
        transition: height 300ms ease-out 0s;
        color: #626262;
        z-index: 999;
        overflow: hidden;
        /* height: 50px; */
        line-height: 2.5em;
        box-radius: 5px;
    }
</style>