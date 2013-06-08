<?xml version="1.0"?>
    <xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    
    <!--Template for root Element-->
    
    <!--HTML HEAD-->
        <xsl:template match="/">
            <html>
	            <head>
		        <title><xsl:value-of select="descendant::mepHeader/docTitle/titlePart"/></title>
		        <link rel="stylesheet" type="text/css" href="../sanger_styles.css" />
	            </head>
	
    <!-- HTML BODY-->
	            <body>
		            <p class="main_heading">
		             The Public Speeches and Writings of Margaret Sanger, 1911-1959
		            </p>
		            <p class="title_heading">
		            <xsl:value-of select="descendant::mepHeader/docAuthor"/>, "<xsl:apply-templates select="descendant::mepHeader/docTitle/titlePart"/>," <xsl:apply-templates select="descendant::mepHeader/docDate"/>.
		            </p>
		            <p class="headnote">
		            <xsl:value-of select="descendant::headNote"/>
		            </p>
		            
		            <hr />
		
    <!-- Main content of the document-->	
        <xsl:apply-templates/>
    
    <!-- Footer -->
                    <hr />
		            <p>
		            Source: <xsl:apply-templates select="descendant::sourceDesc"/>
		            </p>
		            <p>
		            Subject Terms:
		            </p>
		            <ul>
                    <xsl:apply-templates select="descendant::headNote/index"/>
                    </ul>
		            <p>
		            copyright 2003. Margaret Sanger Project
                    </p>
	            </body>
            </html>
         </xsl:template>
    <!-- End of the root template-->

<!-- Templates for the four second-level elements-->
    <xsl:template match="mepHeader">
        <!--don't display-->
    </xsl:template>

    <xsl:template match="sourceNote">
        <!--don't display-->
    </xsl:template>

    <xsl:template match="headNote">
        <!--don't display-->
    </xsl:template>

    <xsl:template match="docBody">
        <xsl:apply-templates/>
    </xsl:template>


<!-- Templates for sepcific elements -->
    <xsl:template match="head">
	    <xsl:if test="not(@type='sub')">
	        <h1 class="{@rend}"><xsl:apply-templates/></h1>
	    </xsl:if>
	    <xsl:if test="@type='sub'">
	        <h2 class="{@rend}"><xsl:apply-templates/></h2>
	    </xsl:if>
    </xsl:template>

    <xsl:template match="docBody/p">
         <xsl:if test="@rend">
	        <p class="{@rend}"><xsl:apply-templates/></p>
	 </xsl:if>
         <xsl:if test="not(@rend)">
	        <p><xsl:apply-templates/></p>
	 </xsl:if>
    </xsl:template>

    <xsl:template match="sourceDesc">
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="mepHeader/sourceDesc/bibl">
        <xsl:if test="position()=2"><xsl:apply-templates/></xsl:if><xsl:if test="position()=4">, <xsl:apply-templates/></xsl:if>
    </xsl:template>
    
    <xsl:template match="mepHeader/sourceDesc/bibl/title">
        <span class="italics"><xsl:apply-templates/></span>
    </xsl:template>
    
    <xsl:template match="index">
        <li>
		<a href=""><!--this is where subject links should go. Probably.--><xsl:apply-templates select="@level1"/><xsl:apply-templates select="@level2"/><xsl:apply-templates select="@level3"/></a>
        </li>
        </xsl:template>

    <xsl:template match="@level1">
        <xsl:value-of select="."/>
    </xsl:template>

    <xsl:template match="@level2">
         <xsl:text>, </xsl:text><xsl:value-of select="."/>
    </xsl:template>

    <xsl:template match="@level3">
         <xsl:text>, </xsl:text><xsl:value-of select="."/>
    </xsl:template>

    <xsl:template match="del">
        <span class="del"><xsl:apply-templates/></span>
    </xsl:template>

    <xsl:template match="add">
        &#8593;<xsl:apply-templates/>&#8595;
    </xsl:template>

    <xsl:template match="supplied">
        [<em><xsl:apply-templates/></em>]
    </xsl:template>

    <xsl:template match="unclear">
        [<em><xsl:apply-templates/></em>?]
    </xsl:template>

    <xsl:template match="epigraph/title">
        <p class="epigraph_title"><xsl:apply-templates/></p>
    </xsl:template>
    
     <xsl:template match="epigraph/p">
        <div class="epigraph_line"><xsl:apply-templates/></div>
    </xsl:template>
    
    <xsl:template match="emph">
        <span class="{@rend}"><xsl:apply-templates/></span>
    </xsl:template>

    <xsl:template match="byline">
         <xsl:if test="@rend">
	        <h3 class="{@rend}"><xsl:apply-templates/></h3>
	 </xsl:if>
         <xsl:if test="not(@rend)">
	        <h3><xsl:apply-templates/></h3>
	 </xsl:if>
    </xsl:template>

    <xsl:template match="title">
         <xsl:if test="@rend">
	        <span class="{@rend}"><xsl:apply-templates/></span>
	 </xsl:if>
         <xsl:if test="not(@rend)">
	        <xsl:apply-templates/>
	 </xsl:if>
    </xsl:template>
   
    <!-- End of Stylesheet -->
    
</xsl:stylesheet>
