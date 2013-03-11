function tab_change_focus(element, element2, element3, element4) {
    if (element2 == "1")
    {

        document.getElementById('li_1').classList.remove('active');
        document.getElementById('li_2').classList.remove('active');
        document.getElementById('li_3').classList.remove('active');
        document.getElementById('li_4').classList.remove('active');
        document.getElementById('li_5').classList.remove('active');
        document.getElementById('li_6').classList.remove('active');
        document.getElementById('li_7').classList.remove('active');
        document.getElementById('li_8').classList.remove('active');

        if(element3 == "1")
        {
            document.getElementById('li_9').classList.remove('active');
        }
        else if(element3 == "2")
        {
            document.getElementById('li_10').classList.remove('active');
            document.getElementById('li_11').classList.remove('active');
            document.getElementById('li_12').classList.remove('active');
            document.getElementById('li_15').classList.remove('active');
            document.getElementById('li_16').classList.remove('active');

            if(element4 == "1")
            {
                document.getElementById('li_13').classList.remove('active');
                document.getElementById('li_14').classList.remove('active');
            }
        }

        document.getElementById(element).classList.add('active');
    }
    else if (element2 == "2") {
        document.getElementById('li1').classList.remove('active');
        document.getElementById('li2').classList.remove('active');

        document.getElementById(element).classList.add('active');
    }
    else if (element2 == "3") {

        document.getElementById('li_1f').classList.remove('active');
        document.getElementById('li_2f').classList.remove('active');
        document.getElementById('li_3f').classList.remove('active');
        document.getElementById('li_4f').classList.remove('active');
        document.getElementById('li_6f').classList.remove('active');

        if(element3 == "1")
        {
            document.getElementById('li_5f').classList.remove('active');
        }

        document.getElementById(element).classList.add('active');
    }
    else if(element2 == "4")
    {
        document.getElementById('li_1t').classList.remove('active');
        document.getElementById('li_2t').classList.remove('active');

        document.getElementById(element).classList.add('active');
    }
    else if (element2 == "5") {
        document.getElementById('li11').classList.remove('active');
        document.getElementById('li22').classList.remove('active');

        document.getElementById(element).classList.add('active');
    }
    else if (element2 == "6") {
        document.getElementById('li1').classList.remove('active');
        document.getElementById('li2').classList.remove('active');

        document.getElementById(element).classList.add('active');
    }
    else if (element2 == "7")
    {
        document.getElementById('li_1m').classList.remove('active');
        document.getElementById('li_2m').classList.remove('active');

        if(element3 == "1")
        {
            document.getElementById('li_3m').classList.remove('active');
        }

        document.getElementById(element).classList.add('active');
    }
    else if(element2 == "8")
    {
        document.getElementById('li_1p').classList.remove('active');
        document.getElementById('li_2p').classList.remove('active');
        document.getElementById('li_3p').classList.remove('active');

        document.getElementById(element).classList.add('active');
    }
}